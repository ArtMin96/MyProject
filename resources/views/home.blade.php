@extends('layouts.app')

@section('headerBox')
    <div class="row align-items-center py-4">

    </div>
    <!-- Card stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Sale </h5>
                            <span class="h2 font-weight-bold mb-0">{{$orders->sum('amount')}} ֏</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                <i class="ni ni-money-coins"></i>
                            </div>
                        </div>
                    </div>
{{--                    <p class="mt-3 mb-0 text-sm">--}}
{{--                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>--}}
{{--                        <span class="text-nowrap">Since last month</span>--}}
{{--                    </p>--}}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Users</h5>
                            <span class="h2 font-weight-bold mb-0">{{$users->count()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                <i class="ni ni ni-single-02"></i>
                            </div>
                        </div>
                    </div>
{{--                    <p class="mt-3 mb-0 text-sm">--}}
{{--                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>--}}
{{--                        <span class="text-nowrap">Since last month</span>--}}
{{--                    </p>--}}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sales Count</h5>
                            <span class="h2 font-weight-bold mb-0">{{$orders->count()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="ni ni-chart-bar-32"></i>
                            </div>
                        </div>
                    </div>
{{--                    <p class="mt-3 mb-0 text-sm">--}}
{{--                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>--}}
{{--                        <span class="text-nowrap">Since last month</span>--}}
{{--                    </p>--}}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Products</h5>
                            <span class="h2 font-weight-bold mb-0">{{$products->count()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="ni ni-bag-17"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js" integrity="sha512-TW5s0IT/IppJtu76UbysrBH9Hy/5X41OTAbQuffZFU6lQ1rdcLHzpU5BzVvr/YFykoiMYZVWlr/PX1mDcfM9Qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@php
$period = now()->subMonths(7)->monthsUntil(now());

$datesWithData = [];

foreach ($period as $date)
{
    $usersCount = \App\User::whereYear('created_at', '=', $date->year)
              ->whereMonth('created_at', '=', $date->format('m'))->count();
    $sales = \App\Models\Orders::whereYear('created_at', '=', $date->year)
    ->whereMonth('created_at', '=', $date->format('m'))->where('status','Completed')->sum('amount');
    $datesWithData[] = [
       'month' => $date->shortMonthName.'/'.$date->year,
       'count' => $usersCount,
       'sales' => $sales,
   ];
}
$dates = array_column($datesWithData,'month');
$count = array_column($datesWithData,'count');
$sales = array_column($datesWithData,'sales');
@endphp
    <div class="row">
        <div class="col-xl-8">
            <div class="card bg-default">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                            <h5 class="h3 text-white mb-0">Users</h5>
                        </div>
                                      </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="total-users"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                            <h5 class="h3 mb-0">Total orders</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <div class="col-12">
                            <canvas id="total-sales"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var dates = <?php echo json_encode($dates); ?>;
        var stats = <?php echo json_encode($count); ?>;
        var ctx = document.getElementById('total-users').getContext('2d');
    var total_users = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: dates,
            datasets: [{
                label: "Monthly Growth",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: stats,
            }]
        },

        // Configuration options go here
        options: {
            maintainAspectRatio: false,
        }
    });

        </script>
        <script>
        var dates = <?php echo json_encode($dates); ?>;
        var sales = <?php echo json_encode($sales); ?>;
        var ctx = document.getElementById('total-sales').getContext('2d');
    var total_users = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',

        // The data for our dataset
        data: {
            labels: dates,
            datasets: [{
                label: "Monthly Sales",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: sales,
            }]
        },

        // Configuration options go here
        options: {
            maintainAspectRatio: false,
        }
    });

        </script>


@stop

