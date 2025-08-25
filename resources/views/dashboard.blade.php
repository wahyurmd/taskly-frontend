<!-- Connect to master template -->
@extends('template.master')

<!-- Title -->
@section('title', 'Taskly | Dashboard')

<!-- Content -->
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info text-center">
                        <a href="https://zenquotes.io/" target="_blank"><h5><i class="fas fa-quote-left"></i> Quote of the Day</h5></a>
                        <p class="mb-0">{{ $today }}</p>
                        <p class="mb-0"><em>"{{ $quote }}"</em></p>
                        <small>{{ $author }}</small>
                    </div>
                </div>
            </div>
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-4 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Task</span>
                            <span class="info-box-number">{{ $totalTask }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Completed</span>
                            <span class="info-box-number">{{ $totalCompleted  }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Not Completed</span>
                            <span class="info-box-number">{{ $totalNotCompleted }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Task Calendar</h3>
                            <div class="card-tools">
                                <select id="filterStatus" class="form-control form-control-sm">
                                    <option value="">All</option>
                                    <option value="0">Not Completed</option>
                                    <option value="1">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="task-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('task-calendar');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                let status = document.getElementById('filterStatus').value;
                let url = API_BASE_URL + "/tasks";

                if (status !== "") {
                    url += "?status=" + status;
                }

                fetch(url, {
                    headers: {
                        'Authorization': "Bearer {{ session('access_token') }}"
                    }
                })
                .then(res => res.json())
                .then(res => {
                    let tasks = res.data || [];
                    let events = tasks.map(task => ({
                        id: task.id,
                        title: task.title,
                        start: task.plan_date,
                        color: task.status == 1 ? '#28a745' : '#dc3545'
                    }));
                    successCallback(events);
                })
                .catch(err => failureCallback(err));
            },
            eventClick: function(info) {
                alert(
                    "Title: " + info.event.title +
                    "\nDescription: " + (props.description ?? '-') +
                    "\nPlan Date: " + props.plan_date +
                    "\nStatus: " + (props.status == 1 ? 'Completed' : 'Not Completed')
                );
            }
        });

        calendar.render();

        // reload jika filter status berubah
        document.getElementById('filterStatus').addEventListener('change', function() {
            calendar.refetchEvents();
        });
    });
</script>
@endsection
