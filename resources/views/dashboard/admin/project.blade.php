@extends('layouts.main')

@section('container')
    <h1>Project</h1>
    <hr/>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="project_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->project->project_name }}</td>
                                <td>
                                    <a href="/dashboard/admin/project/{{ encrypt($project->project_id) }}" class="btn btn-secondary"><ion-icon name="search"></ion-icon> Detail</a>
                                    <a href="/dashboard/admin/articleStatus?pid={{ encrypt($project->project_id) }}" class="btn btn-primary"><ion-icon name="bookmarks"></ion-icon> Article Status</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // Datatable
        var table = $('#project_table').DataTable();
    </script>
@endsection