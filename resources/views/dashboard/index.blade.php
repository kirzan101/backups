@extends('layouts.admin')

@section('title')
    VoucherMS
@endsection

@section('content')

    <div id="app">
        <h3 class="display-4">Dashboard</h3>
        <hr>

        <div class="row">
            <div class="col-12">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
            <form method="GET" action="/dashboard" class="form-inline">
                <label class="my-1 mr-2" for="year">Year: </label>
                <select class="custom-select my-1 mr-sm-2 col-sm-2" id="year" name="year" v-model="year" @change="changeYear()">
                    @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </form>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Collections</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="collections" width="100%" height="25"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Vouchers</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="vouchers" width="100%" height="25"></canvas>
                    </div>
                </div>
            </div>
        </div> 
        
        <div class="row mb-3">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Voucher Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="voucher_status"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Account Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="account_status"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/vue@2.5.16/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

      
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                year: '',
            },
            methods: {
                render_collections(data) { new Chart(document.getElementById("collections"), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.collections,
                            label: "Collections",
                            lineTension: 0.3,
                            backgroundColor: "transparent",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 20,
                            pointBorderWidth: 2,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                unit: 'date'
                                },
                                gridLines: {
                                display: false
                                },
                                ticks: {
                                maxTicksLimit: 7
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                min: 0,
                                max: 40000,
                                maxTicksLimit: 5
                                },
                                gridLines: {
                                color: "rgba(0, 0, 0, .125)",
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });
                },

                render_vouchers(data){ new Chart(document.getElementById("vouchers"), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [                            
                            {
                                label: "Redeemed",
                                data: data.redeemed,
                                type: 'line',
                                fill: false,
                                borderColor: "rgba(2, 204, 32, 1)",
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(2, 204, 32,1)",
                                pointBorderColor: "rgba(255,255,255,0.8)",
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(1, 158, 24,1)",
                                pointHitRadius: 20,
                                pointBorderWidth: 2,
                            },
                            {
                                label: "Issued",
                                backgroundColor: "rgba(2,117,216,1)",
                                borderColor: "rgba(2,117,216,1)",
                                data: data.issued,
                            }
                        ],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'month'
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: Math.max.apply(null, data.issued) + 3,
                                    maxTicksLimit: 5
                                },
                                gridLines: {
                                    color: "rgba(0, 0, 0, .125)",
                                }
                            }],
                        },
                        legend: {
                            display: true
                        }
                    }
                });
                },

                render_voucherStatus(data){ new Chart(document.getElementById("voucher_status"), {
                        type: 'pie',

                        data: {
                            labels: ["Unused", "Redeemed", "Canceled", "Forfeited"],
                            datasets: [
                                {
                                    label: 'Vouchers',
                                    backgroundColor: ['#007bff', '#00c62b', '#dc3545', '#f4e402'],
                                    data: [data.unused, data.redeemed, data.canceled, data.forfeited],
                                }
                            ]
                        },

                        options: {
                            legend: {
                                display: true
                            }
                        }
                    });
                },

                render_accountStatus(data){ new Chart(document.getElementById("account_status"), {
                        type: 'pie',

                        data: {
                            labels: ["Active", "Inactive"],
                            datasets: [
                                {
                                    label: 'Members',
                                    backgroundColor: ['#007bff','#dc3545'],
                                    data: [data.active, data.inactive],
                                }
                            ]
                        },

                        options: {
                            legend: {
                                display: true
                            }
                        }
                    });
                },

                changeYear: function() {
                    var year = this.year;

                    var collections = "{{ url('/dashboard/getCollections') }}/" + year;
                    axios.get(collections).then(response => {
                        this.render_collections(response.data);
                    });

                    var vouchers = "{{ url('/dashboard/getVouchers') }}/" + year;
                    axios.get(vouchers).then(response => {
                        this.render_vouchers(response.data);
                        console.log(response.data);
                    });
                },
            },

            mounted() {
                this.year = "{{ date('Y') }}";
                var year = this.year;

                var collections = "{{ url('/dashboard/getCollections') }}/" + year;
                axios.get(collections).then(response => {
                    this.render_collections(response.data);
                });

                var vouchers = "{{ url('/dashboard/getVouchers') }}/" + year;
                axios.get(vouchers).then(response => {
                    this.render_vouchers(response.data);
                });

                var voucher_status = "{{ url('/dashboard/voucherStatus') }}";
                axios.get(voucher_status).then(response => {
                    this.render_voucherStatus(response.data);
                });

                var account_status = "{{ url('/dashboard/accountStatus') }}";
                axios.get(account_status).then(response => {
                    this.render_accountStatus(response.data);
                });
            }
        });
    </script>
@endsection