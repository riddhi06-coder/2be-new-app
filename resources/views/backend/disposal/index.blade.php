<!doctype html>
<html lang="en">
    
<head>
    @include('components.backend.head')
</head>
	   
		@include('components.backend.header')

	    <!--start sidebar wrapper-->	
	    @include('components.backend.sidebar')
	   <!--end sidebar wrapper-->

    
     <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                <div class="card">
                    
                    <div class="card-body">
                        <!-- Top Controls -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('manage-disposal-details.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Disposal Details
                                </li>
                            </ol>
                        </nav>

                        <!-- Right Controls -->
                        <div class="d-flex align-items-center flex-wrap gap-2 mt-3">

                            <!-- Export CSV -->
                            <a href="{{ route('manage-disposal-details.export', request()->all()) }}"
                            class="btn btn-sm btn-success">
                                Export
                            </a>

                            <!-- Export PDF -->
                            <form method="POST"
                                action="{{ route('manage-disposal-details.exportSelectedPdf') }}"
                                id="exportForm"
                                class="d-flex align-items-center">
                                @csrf
                                <input type="hidden" name="selected_ids" id="selected_ids">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Generate PDF
                                </button>
                            </form>

                            <!-- Filter Form -->
                            <form method="GET"
                                action="{{ route('manage-disposal-details.index') }}"
                                class="d-flex align-items-center gap-2">

                                <!-- From Date -->
                                <div class="d-flex flex-column">
                                    <label class="small fw-semibold text-muted mb-1">From Date</label>
                                    <input type="date"
                                        name="from_date"
                                        value="{{ request('from_date') }}"
                                        class="form-control form-control-sm"
                                        style="width:170px; margin-bottom: 20px;">
                                </div>

                                <!-- To Date -->
                                <div class="d-flex flex-column">
                                    <label class="small fw-semibold text-muted mb-1">To Date</label>
                                    <input type="date"
                                        name="to_date"
                                        value="{{ request('to_date') }}"
                                        class="form-control form-control-sm"
                                        style="width:170px; margin-bottom: 20px;">
                                </div>

                                <!-- Year -->
                                <div class="d-flex flex-column">
                                    <label class="small fw-semibold text-muted mb-1">Year</label>
                                    <select name="year"
                                            class="form-select form-select-sm"
                                            style="width:130px; margin-bottom: 20px;">
                                        <option value="">All</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Filter Button -->
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Filter
                                </button>

                                <!-- Reset -->
                                <a href="{{ route('manage-disposal-details.index') }}"
                                class="btn btn-sm btn-secondary">
                                    Reset
                                </a>

                            </form>

                        </div>
                    </div>


                    <div class="table-responsive custom-scrollbar mt-5">
                    
                        <table class="display" id="basic-1">
                        
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>#</th>

                                    <th>Date of Pickup</th>
                                    <th>Generator Name</th>
                                    <th>Waste Type</th>
                                    <th>Volume Pumped <br>(In Gallons)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disposals as $index => $disposal)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                class="row-checkbox"
                                                name="ids[]"
                                                value="{{ $disposal->id }}">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($disposal->date_of_pickup)->format('d-m-Y') }}</td>
                                        <td>{{ $disposal->generator_name }}</td>
                                        <td>{{ $disposal->waste_type }}</td>
                                        <td>{{ $disposal->volume_pumped }}</td>
                                        <td>
                                            <a href="{{ route('manage-disposal-details.edit', $disposal->id) }}" class="btn btn-sm btn-primary">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>
    

        @include('components.backend.main-js')


        <script>
           document.getElementById('exportForm').addEventListener('submit', function (e) {
                let ids = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => ids.push(cb.value));

                if (ids.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one record');
                    return;
                }

                document.getElementById('selected_ids').value = ids.join(',');

                // Show loader
                const loader = document.getElementById('pdfLoader');
                loader.style.display = 'flex';

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                // ✅ Reset UI after reasonable time
                setTimeout(() => {
                    loader.style.display = 'none';
                    submitBtn.disabled = false;
                    
                    // ✅ Reload page after download time
                    window.location.reload();

                }, 8000); // adjust based on PDF size
            });

            // Select All
            document.getElementById('select-all').addEventListener('change', function () {
                document.querySelectorAll('.row-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        </script>

        <div id="pdfLoader"
            style="display:none;
                    position:fixed;
                    top:0;
                    left:0;
                    width:100%;
                    height:100%;
                    background:rgba(255,255,255,0.8);
                    z-index:9999;
                    align-items:center;
                    justify-content:center;">

            <div class="text-center">
                <div class="spinner-border text-danger mb-3" role="status"></div>
                <div class="fw-semibold">Generating PDF, please wait…</div>
            </div>
        </div>

</body>

</html>