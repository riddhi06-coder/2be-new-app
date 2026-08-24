{{--
    Reusable SweetAlert flash-message handler (frontend) — default SweetAlert2 styling.
    Trigger from any controller with:
        ->with('message'|'success', '...')   // success
        ->with('error', '...')               // error
        ->with('info', '...')                // info
    Validation errors are shown automatically too.
    Included globally via components/frontend/main-js — no per-page code needed.
--}}
@php
    $swalSuccess = session('message') ?? session('success');
    $swalError   = session('error') ?? ($errors->any() ? $errors->first() : null);
    $swalInfo    = session('info');
@endphp

@if($swalSuccess || $swalError || $swalInfo)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if($swalSuccess)
            Swal.fire({ icon: 'success', title: @json($swalSuccess) });
        @elseif($swalError)
            Swal.fire({ icon: 'error', title: 'Oops!', text: @json($swalError) });
        @elseif($swalInfo)
            Swal.fire({ icon: 'info', title: @json($swalInfo) });
        @endif
    </script>
@endif
