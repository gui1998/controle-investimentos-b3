@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Page Heading -->
                <h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Editar Setor</h1>
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <form action="{{route('sectors.update', $sector->id)}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="font-weight-bold">Nome:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $sector->name) }}">
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
                window.location.href = '../../sectors';
            });
        });
    </script>
@endsection
