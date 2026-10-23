<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>Employees</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('employees.create'))
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">+ New Employee</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-1" class="display table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Quarterly Assessment</th>
                                        <th class="text-end" style="min-width:170px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $emp)
                                        <tr>
                                            <td>{{ $emp->name }}</td>
                                            <td>{{ $emp->email }}</td>
                                            <td>
                                                @if($emp->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(! $emp->start_date)
                                                    <span class="text-muted">No start date</span>
                                                @else
                                                    @php $nextDue = $emp->nextAssessmentDueDate(); @endphp
                                                    @if($emp->isAssessmentDue())
                                                        <span class="badge bg-danger">Due</span>
                                                        <div class="small text-muted">since {{ $nextDue->format('M j, Y') }}</div>
                                                    @else
                                                        <span class="badge bg-success">On track</span>
                                                        <div class="small text-muted">due {{ $nextDue->format('M j, Y') }}</div>
                                                    @endif
                                                    @if($emp->last_assessment_at)
                                                        <div class="small text-muted">last: {{ $emp->last_assessment_at->format('M j, Y') }}</div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if(auth()->user()->hasPermission('employees.edit'))
                                                        <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('employees.delete'))
                                                        <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this employee?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No employees yet. Click &ldquo;New Employee&rdquo; to add one.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')
</body>
</html>
