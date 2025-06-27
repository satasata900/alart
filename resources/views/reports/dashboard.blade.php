@extends('layouts.app')

@section('title', 'لوحة معلومات البلاغات')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">لوحة التحكم /</span> البلاغات
    </h4>

    <!-- بطاقات الإحصائيات -->
    <div class="row">
        <div class="col-md-3 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">إجمالي البلاغات</span>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 me-2">{{ $totalReports }}</h3>
                                <span class="badge bg-label-primary">بلاغ</span>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-report ti-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">بلاغات اليوم</span>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 me-2">{{ $todayReports }}</h3>
                                <span class="badge bg-label-success">بلاغ</span>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-calendar ti-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">بلاغات عالية الأولوية</span>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 me-2">{{ $urgentReports }}</h3>
                                <span class="badge bg-label-danger">بلاغ</span>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ti ti-alert-circle ti-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-medium d-block mb-1">بلاغات مكتملة</span>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 me-2">{{ $completedReports }}</h3>
                                <span class="badge bg-label-info">بلاغ</span>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-check ti-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر البلاغات -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">آخر البلاغات الواردة</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">عرض كافة البلاغات</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نوع البلاغ</th>
                                <th>درجة الإلحاح</th>
                                <th>موقع البلاغ</th>
                                <th>تاريخ البلاغ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($latestReports as $report)
                            <tr>
                                <td>{{ $report->code }}</td>
                                <td>{{ $report->reportType->name }}</td>
                                <td>
                                    @if($report->urgency_level == 'high')
                                        <span class="badge bg-danger">عالي</span>
                                    @elseif($report->urgency_level == 'medium')
                                        <span class="badge bg-warning">متوسط</span>
                                    @else
                                        <span class="badge bg-info">منخفض</span>
                                    @endif
                                </td>
                                <td>{{ $report->location }}</td>
                                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($report->status == 'new')
                                        <span class="badge bg-label-primary">جديد</span>
                                    @elseif($report->status == 'in_progress')
                                        <span class="badge bg-label-warning">قيد المعالجة</span>
                                    @elseif($report->status == 'completed')
                                        <span class="badge bg-label-success">تم</span>
                                    @else
                                        <span class="badge bg-label-secondary">مؤجل</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-icon btn-sm btn-info me-1" title="عرض">
                                            <span class="ti ti-eye"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد بلاغات حتى الآن</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- توزيع البلاغات حسب المنطقة -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">توزيع البلاغات حسب المنطقة</h5>
                        <small class="text-muted">عدد البلاغات في كل منطقة عمليات</small>
                    </div>
                </div>
                <div class="card-body">
                    <div id="reportsPerAreaChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">توزيع البلاغات حسب النوع</h5>
                        <small class="text-muted">عدد البلاغات لكل نوع</small>
                    </div>
                </div>
                <div class="card-body">
                    <div id="reportsPerTypeChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(function() {
        // بيانات توزيع البلاغات حسب المنطقة
        const areaChartOptions = {
            series: [{
                name: 'عدد البلاغات',
                data: @json($reportsByAreaData['counts'])
            }],
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'inherit',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($reportsByAreaData['areas']),
                labels: {
                    style: {
                        fontFamily: 'inherit'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'عدد البلاغات',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                labels: {
                    style: {
                        fontFamily: 'inherit'
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " بلاغ"
                    }
                }
            },
            theme: {
                mode: $('html').attr('data-theme') === 'dark' ? 'dark' : 'light'
            }
        };

        const areaChart = new ApexCharts(document.querySelector("#reportsPerAreaChart"), areaChartOptions);
        areaChart.render();

        // بيانات توزيع البلاغات حسب النوع
        const typeChartOptions = {
            series: @json($reportsByTypeData['counts']),
            chart: {
                width: '100%',
                type: 'pie',
                fontFamily: 'inherit'
            },
            labels: @json($reportsByTypeData['types']),
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            theme: {
                mode: $('html').attr('data-theme') === 'dark' ? 'dark' : 'light'
            }
        };

        const typeChart = new ApexCharts(document.querySelector("#reportsPerTypeChart"), typeChartOptions);
        typeChart.render();
    });
</script>
@endsection
