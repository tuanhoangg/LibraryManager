@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Overdue Books -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $data['overdue'] }}</h3>
                        <p>Quá hạn trả</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-alert-circled"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $data['pending'] }}</h3>
                        <p>Chờ phê duyệt</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Currently Borrowed -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $data['borrowed'] }}</h3>
                        <p>Đang mượn</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-book"></i>
                    </div>
                </div>
            </div>

            <!-- Lost Books -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $data['lost'] }}</h3>
                        <p>Mất</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-close"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <canvas id="borrowedBooksChart"></canvas>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <canvas id="genreDistributionChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('genreDistributionChart').getContext('2d');
            var genreDistributionChart = new Chart(ctx, {
                type: 'doughnut', // Bạn có thể chọn 'pie' nếu muốn
                data: {
                    labels: {!! json_encode($labelsBook) !!},
                    datasets: [{
                        data: {!! json_encode($dataBook) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Genre Distribution in Library'
                        }
                    }
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('borrowedBooksChart').getContext('2d');
            var borrowedBooksChart = new Chart(ctx, {
                type: 'line', // Bạn có thể chọn loại biểu đồ khác như 'bar', 'pie', 'doughnut', v.v.
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [{
                        label: 'Books Borrowed',
                        data: {!! json_encode($totals) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1,
                        fill: true // Để tô màu phía dưới đường biểu đồ
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Number of Books Borrowed Each Day'
                        }
                    }
                }
            });
        });
    </script>
@endpush
