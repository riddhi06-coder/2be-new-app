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
                  <h4>Add Email Setting Form</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('manage-email-settings.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Email Setting</li>
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
                        <h4>Email Setting Form</h4>
                        <p class="f-m-light mt-1">Fill up your true details and submit the form.</p>
                    </div>
                    <div class="card-body">
                        <div class="vertical-main-wizard">
                        <div class="row g-3">    
                            <!-- Removed empty col div -->
                            <div class="col-12">
                            <div class="tab-content" id="wizard-tabContent">
                                <div class="tab-pane fade show active" id="wizard-contact" role="tabpanel" aria-labelledby="wizard-contact-tab">
                                    <form class="row g-3 needs-validation custom-input" novalidate action="{{ route('manage-email-settings.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf


                                        <!-- Default Email -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label" for="default_email">Default Email <span class="text-danger">*</span> </label>
                                            <input class="form-control" id="default_email" type="email" name="default_email" placeholder="Enter Default Email" required>
                                            <div class="invalid-feedback">Please enter a Default Email.</div>
                                        </div>

                                        <!-- Email 1 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label" for="email1">Email 1 <span class="text-danger">*</span> </label>
                                            <input class="form-control" id="email1" type="email" name="email1" placeholder="Enter Email 1" required>
                                            <div class="invalid-feedback">Please enter a Email 1.</div>
                                        </div>


                                        <!-- Email 2 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label" for="email2">Email 2 (Optional)</label>
                                            <input class="form-control" id="email2" type="email" name="email2" placeholder="Enter Email 2">
                                            <div class="invalid-feedback">Please enter a Email 2.</div>
                                        </div>


                                        <!-- Email 3 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label" for="email3">Email 3 (Optional) </label>
                                            <input class="form-control" id="email3" type="email" name="email3" placeholder="Enter Email 3">
                                            <div class="invalid-feedback">Please enter a Email 3.</div>
                                        </div>



                                        <!-- Form Actions -->
                                        <div class="col-12 text-end">
                                            <a href="{{ route('manage-email-settings.index') }}" class="btn btn-danger px-4">Cancel</a>
                                            <button class="btn btn-primary" type="submit">Submit</button>
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