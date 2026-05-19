<!doctype html>
<html lang="en">
    
<head>
    @include('components.backend.head')

      <style>
        body {
            background: #f4f6f9;
        }

        .qa-card {
            background: #bfaeae;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.06);
        }

        .qa-header h4 {
            font-weight: 600;
        }

        .qa-actions {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .qa-item {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 22px;
            border-radius: 14px;
            background: #f9fafb;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid transparent;
        }

        .qa-item:hover {
            background: #ffffff;
            border-color: #e5e7eb;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .qa-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .qa-icon.green {
            background: #16a34a;
        }

        .qa-icon.blue {
            background: #2563eb;
        }

        .qa-content {
            flex-grow: 1;
        }

        .qa-title {
            font-weight: 600;
            color: #111827;
        }

        .qa-sub {
            font-size: 14px;
            color: #6b7280;
        }

        .qa-arrow {
            font-size: 18px;
            color: #9ca3af;
            transition: transform 0.2s ease;
        }

        .qa-item:hover .qa-arrow {
            transform: translateX(5px);
        }

      </style>

</head>
	   
		@include('components.backend.header')

	  <!--start sidebar wrapper-->	
	  @include('components.backend.sidebar')
	  <!--end sidebar wrapper-->



      <div class="page-body"> 
          <div class="container-fluid">            
            <div class="page-title"> 
              <div class="row">
                
                
              </div>
            </div>
          </div>



          <div class="container py-5">
              <div class="row justify-content-center">
                  <div class="col-lg-6">

                      <div class="qa-card">

                          <div class="qa-header text-center mb-4">
                              <h4>Quick Actions</h4>
                              <p class="text-muted mb-0">Manage waste records and reports</p>
                          </div>

                          <div class="qa-actions">

                              <a href="{{ route('frontend.log_waste_disposal') }}"
                                target="_blank"
                                class="qa-item">

                                  <div class="qa-icon green">
                                      ♻
                                  </div>

                                  <div class="qa-content">
                                      <div class="qa-title">Log Waste Disposal</div>
                                      <div class="qa-sub">Add a new waste collection record</div>
                                  </div>

                                  <div class="qa-arrow">
                                      →
                                  </div>

                              </a>

                              <a href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#monthlyReportModal"
                                class="qa-item">

                                  <div class="qa-icon blue">
                                      📊
                                  </div>

                                  <div class="qa-content">
                                      <div class="qa-title">Generate Monthly Report</div>
                                      <div class="qa-sub">Download disposal summary</div>
                                  </div>

                                  <div class="qa-arrow">
                                      →
                                  </div>

                              </a>

                          </div>

                      </div>

                  </div>
              </div>
          </div>

          <!-- Container-fluid Ends -->
          </div>
        <!-- footer start-->
        @include('components.backend.footer')
      </div>
      
    </div>


    <div class="modal fade" id="monthlyReportModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg">

              <!-- Modal Header -->
              <div class="modal-header bg-light">
                  <h5 class="modal-title fw-bold">
                      Generate Monthly Source Report
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <!-- Modal Body -->
              <form method="POST" action="{{ route('generate.monthly.report') }}" id="monthlyReportForm">
                  @csrf

                  <div class="modal-body">
                      <div class="row g-3">


                          @php
                              $availableMonths = DB::table('waste_disposal_details')
                                  ->selectRaw('YEAR(date_of_pickup) as year, MONTH(date_of_pickup) as month')
                                  ->groupBy('year', 'month')
                                  ->orderBy('year', 'desc')
                                  ->orderBy('month', 'desc')
                                  ->get();
                          @endphp

                          <!-- Year -->
                          <div class="col-md-6">
                              <label class="form-label">Year</label>
                              <select name="year" id="yearSelect" class="form-select" required>
                                  <option value="">Select Year</option>

                                  @foreach($availableMonths->pluck('year')->unique() as $year)
                                      <option value="{{ $year }}">{{ $year }}</option>
                                  @endforeach

                              </select>
                          </div>

                          <!-- Month -->
                          <div class="col-md-6">
                            <label class="form-label">Month</label>
                            <select name="month" id="monthSelect" class="form-select" required disabled>
                                <option value="">Select Month</option>

                                @foreach($availableMonths as $item)
                                    <option value="{{ $item->month }}"
                                            data-year="{{ $item->year }}">
                                        {{ \Carbon\Carbon::createFromDate($item->year, $item->month, 1)->format('F') }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                          <!-- SOH DOH Registration -->
                          <div class="col-md-6">
                              <label class="form-label">SOH DOH Registration Number</label>
                              <input type="text"
                                    name="soh_doh_registration"
                                    class="form-control"
                                    placeholder="Enter SOH DOH Registration #"
                                    required>
                          </div>

                          <!-- COH Permit -->
                          <div class="col-md-6">
                              <label class="form-label">COH Permit #</label>
                              <input type="text"
                                    name="coh_permit"
                                    class="form-control"
                                    placeholder="Enter COH Permit #"
                                    required>
                          </div>

                          <!-- Signed By -->
                          <div class="col-md-6">
                              <label class="form-label">Signed By</label>
                              <input type="text"
                                    name="signed_by"
                                    class="form-control"
                                    placeholder="Full Name"
                                    required>
                          </div>

                          <!-- Title -->
                          <div class="col-md-6">
                              <label class="form-label">Title</label>
                              <input type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="Title / Position"
                                    required>
                          </div>

                          <!-- Date -->
                          <div class="col-md-6">
                              <label class="form-label">Date</label>
                              <input type="date"
                                    name="signed_date"
                                    class="form-control"
                                    value="{{ now()->toDateString() }}"
                                    required>
                          </div>

                      </div>
                  </div>

                  <!-- Modal Footer -->
                  <div class="modal-footer bg-light">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                          Cancel
                      </button>
                      <button type="submit" class="btn btn-primary" id="generateBtn">
                          Generate PDF Report
                      </button>
                  </div>

              </form>

          </div>
      </div>
    </div>

        
    
    @include('components.backend.main-js')

    <!---- SUbmit button validations---->
    <script>
      document.getElementById('monthlyReportForm').addEventListener('submit', function () {

          let btn = document.getElementById('generateBtn');

          btn.disabled = true;
          btn.innerHTML = 'Generating...';

          // Reload page after 3 seconds
          setTimeout(function () {
              window.location.reload();
          }, 3000);

      });
    </script>

    
    <!---- Dynamic month fetching as per the year---->
    <script>
      document.getElementById('yearSelect').addEventListener('change', function () {

          let selectedYear = this.value;
          let monthSelect = document.getElementById('monthSelect');
          let options = monthSelect.querySelectorAll('option');

          monthSelect.value = "";
          monthSelect.disabled = true;

          options.forEach(function(option) {

              if (option.value === "") return;

              if (option.getAttribute('data-year') === selectedYear) {
                  option.style.display = "block";
                  monthSelect.disabled = false;
              } else {
                  option.style.display = "none";
              }
          });

      });
    </script>

        
</body>

</html>