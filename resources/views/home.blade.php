@extends('layouts.admin')

@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-sm-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total da carteira
                            </div>
                            <div id="totalValue" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-sm-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Resultado do mes
                            </div>
                            <div id="monthResult" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-sm-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Rendimentos do mês
                            </div>
                            <div id="incomeResult" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Content Column -->
        <div class="col-lg mb">


            <div class="row">
                <!-- Pie Chart -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Quantidade por Setor</h6>
                            <div class="dropdown no-arrow">
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div id="pieFooter" class="mt-4 text-center small">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- type Chart -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Quantidade por Tipo</h6>
                            <div class="dropdown no-arrow">
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="typesPieChart"></canvas>
                            </div>
                            <div id="pieFooter" class="mt-4 text-center small">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Bar Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lucro/Prejuízo dos últimos 6 meses</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <!-- Area Chart -->
        <div class="col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Compra e Venda dos últimos 6 meses</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="chart-container">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        // Area Chart Example
        var barChart = document.getElementById("myAreaChart");
        var sellArray = [];
        var totalSell = 0;
        var totalBuy = 0;
        var buyArray = [];
        var labels = [];

        $.ajax({
            url: '{{route('buy-and-sell-statistics')}}', success: function (result) {
                Object.values(result).forEach(function (e, i) {

                    arrayData = e.map((val) => val);

                    arrayData.forEach(function (valor, key) {
                        if (valor.type === 'S') {
                            totalSell += valor.total;
                            sellArray.push(valor.total);
                            labels.push(valor.month + '/' + valor.year)
                        } else {
                            totalBuy += valor.total;
                            buyArray.push(valor.total);
                        }
                    });
                });

                var data = {
                    labels: labels,
                    datasets: [
                        {
                            label: "Venda",
                            backgroundColor: 'rgba(16,96,1,0.8)',
                            data: sellArray
                        },
                        {
                            label: "Compra",
                            backgroundColor: 'rgba(248,3,3,0.67)',
                            data: buyArray
                        }
                    ]
                };

                var myBarChart = new Chart(barChart, {
                    type: 'bar',
                    data: data,
                    animation: true,
                    scaleOverride: false,
                    scaleSteps: 9,
                    scaleStartValue: 0,
                    scaleStepWidth: 100,
                    options: {
                        tooltips: {
                            callbacks: {
                                label: function (tooltipItems, data) {
                                    return "R$ " + tooltipItems.yLabel;
                                }
                            }
                        }
                    }
                });
            }
        });


        var ctx2 = document.getElementById("myBarChart");
        var values = [];
        var lineLabels = [];
        var chartColors = {
            red: 'rgb(255, 99, 132)',
            blue: 'rgb(54, 162, 235)'
        };

        $.ajax({
            url: '{{route('list-results')}}', success: function (result) {

                Object.values(result).forEach(function (e, i) {
                    values.push(e.total);
                    lineLabels.push(e.month + '/' + e.year)
                });

                ultimo = Object.create(values);
                var ultimo = parseFloat(ultimo.pop());

                $('#monthResult')
                    .text(ultimo.toLocaleString('pt-BR', {
                    maximumFractionDigits: 2,
                    style: 'currency',
                    currency: 'BRL',
                    useGrouping: true
                    }));

                const colours = values.map((value) => value < 0 ? 'red' : value > 0 ? 'green' : 'black');
                var myChart = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: lineLabels,
                        bezierCurve: false,
                        datasets: [
                            {
                                data: values,
                                label: "Lucro/Prejuízo dos últimos 6 meses",
                                lineTension: 0,
                                pointRadius: 4,
                                pointHoverRadius: 10,
                                borderColor: "rgba(73,80,252,0.46)",
                                backgroundColor: colours,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        tooltips: {
                            enabled: true,
                            mode: 'single',
                            callbacks: {
                                label: function (tooltipItems, data) {
                                    return "R$ " + tooltipItems.yLabel;
                                }
                            }
                        }
                    }
                });
            }
        });

        var pieCtx = document.getElementById("myPieChart");
        var pieValues = [];
        var pieLabels = [];
        var totalValues = 0;
        $.ajax({
            url: '{{route('sectors-statistics')}}', success: function (result) {

                Object.values(result).forEach(function (e, i) {
                    pieValues.push(e.total);
                    totalValues += parseFloat(e.total);
                    pieLabels.push(e.name)
                });

                var cont = 0;
                pieLabels = pieLabels.map(function (value) {
                    percentage = (pieValues[cont] / totalValues) * 100;
                    cont++;
                    return percentage.toFixed(2) + '% ' + value
                });
                console.log(pieLabels);
                const colours = pieValues.map((value) => getRandomColor());
                var myChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieValues,
                            lineTension: 0,
                            pointRadius: 4,
                            pointHoverRadius: 10,
                            borderColor: "rgb(0,0,0)",
                            backgroundColor: colours,
                            fill: true
                        }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: true,
                            caretPadding: 10,
                            enabled: true,
                            mode: 'single'
                        },
                        legend: {
                            display: true
                        },
                    }
                });
            }
        });


        var typesPieCtx = document.getElementById("typesPieChart");
        var typesPieValues = [];
        var typesPieLabels = [];
        var typesTotalValues = 0;
        $.ajax({
            url: '{{route('types-statistics')}}', success: function (result) {

                Object.values(result).forEach(function (e, i) {
                    typesPieValues.push(e.total);
                    typesTotalValues += parseFloat(e.total);
                    typesPieLabels.push(e.name)
                });

                var cont = 0;
                typesPieLabels = typesPieLabels.map(function (value) {
                    percentage = (typesPieValues[cont] / typesTotalValues) * 100;
                    cont++;
                    return percentage.toFixed(2) + '% ' + value
                });

                const colours = typesPieValues.map((value) => getRandomColor());
                var myChart = new Chart(typesPieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: typesPieLabels,
                        datasets: [{
                            data: typesPieValues,
                            lineTension: 0,
                            pointRadius: 4,
                            pointHoverRadius: 10,
                            borderColor: "rgb(0,0,0)",
                            backgroundColor: colours,
                            fill: true
                        }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: true,
                            caretPadding: 10,
                            enabled: true,
                            mode: 'single'
                        },
                        legend: {
                            display: true
                        },
                    }
                });

                $('#totalValue').text(typesTotalValues.toLocaleString('pt-BR', {
                    maximumFractionDigits: 2,
                    style: 'currency',
                    currency: 'BRL',
                    useGrouping: true
                }));

            }

        });

        var incomeValues = []

        $.ajax({
            url: '{{route('income-statistics')}}', success: function (result) {
                Object.values(result).forEach(function (e, i) {
                    incomeValues.push(e.total);
                });

                ultimo = incomeValues.pop()

                $('#incomeResult').text(ultimo.toLocaleString('pt-BR', {
                    maximumFractionDigits: 2,
                    style: 'currency',
                    currency: 'BRL',
                    useGrouping: true
                }));


            }});


        function getRandomColor(opacidade = 1) {
            let r = Math.random() * 255;
            let g = Math.random() * 255;
            let b = Math.random() * 255;

            return `rgba(${r}, ${g}, ${b}, ${opacidade})`;
        }

    </script>
@endsection
