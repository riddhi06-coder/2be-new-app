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
        <div class="page-title"><div class="row"><div class="col-12"><h3>Edit Employee — {{ $employee->name }}</h3></div></div></div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(is_null($employee->welcome_email_sent_at))
                            <div class="alert alert-warning">
                                <strong>Welcome email pending.</strong> This employee has not received their credentials yet.
                                Enter a new password below and save to send the welcome email now.
                            </div>
                        @else
                            <div class="alert alert-success py-2">
                                Welcome email was sent on {{ $employee->welcome_email_sent_at->format('d M Y, h:i A') }}. It will not be sent again.
                            </div>
                        @endif
                        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $employee->name) }}" required placeholder="e.g. John Doe">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $employee->email) }}" required placeholder="name@example.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="emp-active" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emp-active">Active <small class="text-muted">(only active employees can log in)</small></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Minimum 8 characters" style="padding-right: 2.5rem;" autocomplete="new-password">
                                        <span class="toggle-password" data-target="password" title="Show/Hide password"
                                              style="position:absolute; top:50%; right:.9rem; transform:translateY(-50%); cursor:pointer; color:#6c757d; z-index:5;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                               placeholder="Re-enter the new password" style="padding-right: 2.5rem;" autocomplete="new-password">
                                        <span class="toggle-password" data-target="password_confirmation" title="Show/Hide password"
                                              style="position:absolute; top:50%; right:.9rem; transform:translateY(-50%); cursor:pointer; color:#6c757d; z-index:5;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Cancel</a>
                        </form>
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
<script>
    document.querySelectorAll('.toggle-password').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById(this.getAttribute('data-target'));
            if (!input) return;
            var icon = this.querySelector('i');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !show);
            icon.classList.toggle('fa-eye-slash', show);
        });
    });
</script>
</body>
</html>
