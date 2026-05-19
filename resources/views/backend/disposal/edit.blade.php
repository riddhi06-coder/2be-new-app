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
                  <h4>View Disposal Details Form</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('manage-disposal-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">View Disposal Details</li>
                </ol>

                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Disposal Details Form</h4>
                        <p class="f-m-light mt-1">Fill up your true details and submit the form.</p>
                    </div>
                    <div class="card-body">
                        <div class="vertical-main-wizard">
                        <div class="row g-3">    
                            <!-- Removed empty col div -->
                            <div class="col-12">
                            <div class="tab-content" id="wizard-tabContent">
                                <div class="tab-pane fade show active" id="wizard-contact" role="tabpanel" aria-labelledby="wizard-contact-tab">

                                    <form class="needs-validation custom-input" novalidate method="POST" enctype="multipart/form-data">

                                        <div class="row g-3">
                                            <!-- Created Date -->
                                            <div class="col-md-6">
                                                <label for="created_at" class="form-label">Created Date</label>
                                                <input type="text" class="form-control" id="created_at" 
                                                    value="{{ \Carbon\Carbon::parse($disposal->created_at)->format('d-m-Y H:i:s') }}" readonly>
                                            </div>

                                            <!-- IP Address -->
                                            <div class="col-md-6">
                                                <label for="ip_address" class="form-label">IP Address</label>
                                                <input type="text" class="form-control" id="ip_address" value="{{ $disposal->ip_address }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- Date of Pickup -->
                                            <div class="col-md-6">
                                                <label for="date_of_pickup" class="form-label">Date of Pickup</label>
                                                <input type="text" class="form-control" id="date_of_pickup" 
                                                    value="{{ \Carbon\Carbon::parse($disposal->date_of_pickup)->format('d-m-Y') }}" readonly>
                                            </div>

                                            <!-- Generator Name -->
                                            <div class="col-md-6">
                                                <label for="generator_name" class="form-label">Generator Name</label>
                                                <input type="text" class="form-control" id="generator_name" value="{{ $disposal->generator_name }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- Waste Type -->
                                            <div class="col-md-6">
                                                <label for="waste_type" class="form-label">Waste Type</label>
                                                <input type="text" class="form-control" id="waste_type" value="{{ $disposal->waste_type }}" readonly>
                                            </div>

                                            <!-- Volume Pumped -->
                                            <div class="col-md-3">
                                                <label for="volume_pumped" class="form-label">Volume Pumped</label>
                                                <input type="text" class="form-control" id="volume_pumped" value="{{ $disposal->volume_pumped }}" readonly>
                                            </div>

                                            <!-- Unit -->
                                            <div class="col-md-3">
                                                <label for="unit" class="form-label">Unit</label>
                                                <input type="text" class="form-control" id="unit" value="{{ $disposal->unit }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- ZIP -->
                                            <div class="col-md-6">
                                                <label for="zip" class="form-label">ZIP</label>
                                                <input type="text" class="form-control" id="zip" value="{{ $disposal->zip }}" readonly>
                                            </div>

                                            <!-- Date of Discharge -->
                                            <div class="col-md-6">
                                                <label for="date_of_discharge" class="form-label">Date of Discharge</label>
                                                <input type="text" class="form-control" id="date_of_discharge" value="{{ $disposal->date_of_discharge }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- Discharge Site -->
                                            <div class="col-md-6">
                                                <label for="discharge_site" class="form-label">Discharge Site</label>
                                                <input type="text" class="form-control" id="discharge_site" value="{{ $disposal->discharge_site }}" readonly>
                                            </div>

                                            <!-- Transporter Name -->
                                            <div class="col-md-6">
                                                <label for="transporter_name" class="form-label">Transporter Name</label>
                                                <input type="text" class="form-control" id="transporter_name" value="{{ $disposal->transporter_name }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- Vehicle License Number -->
                                            <div class="col-md-6">
                                                <label for="vehicle_license_number" class="form-label">Vehicle License Number</label>
                                                <input type="text" class="form-control" id="vehicle_license_number" value="{{ $disposal->vehicle_license_number }}" readonly>
                                            </div>

                                            <!-- Empty placeholder to keep alignment -->
                                            <div class="col-md-6"></div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <!-- Address -->
                                            <div class="col-12">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control" id="address" rows="2" readonly>{{ $disposal->address }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <!-- Form Actions -->
                                            <div class="col-12 text-end">
                                                <a href="{{ route('manage-disposal-details.index') }}" class="btn btn-danger px-4">Back</a>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            </div>
                        </div>
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


</body>

</html>