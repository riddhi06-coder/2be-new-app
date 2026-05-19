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
                  <h4>Edit Email Setting Form</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('manage-email-settings.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Email Setting</li>
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
                                    <form class="row g-3 needs-validation custom-input"
                                        novalidate
                                        action="{{ route('manage-email-settings.update', $email->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('PUT')

                                        <!-- Default Email -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label">Default Email <span class="text-danger">*</span></label>
                                            <input class="form-control"
                                                type="email"
                                                name="default_email"
                                                value="{{ old('default_email', $email->default_email) }}"
                                                required>
                                        </div>

                                        <!-- Email 1 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label">Email 1 <span class="text-danger">*</span></label>
                                            <input class="form-control"
                                                type="email"
                                                name="email1"
                                                value="{{ old('email1', $email->email1) }}"
                                                required>
                                        </div>

                                        <!-- Email 2 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label">Email 2 (Optional)</label>
                                            <input class="form-control"
                                                type="email"
                                                name="email2"
                                                value="{{ old('email2', $email->email2) }}">
                                        </div>

                                        <!-- Email 3 -->
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label">Email 3 (Optional)</label>
                                            <input class="form-control"
                                                type="email"
                                                name="email3"
                                                value="{{ old('email3', $email->email3) }}">
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-12 text-end">
                                            <a href="{{ route('manage-email-settings.index') }}" class="btn btn-danger px-4">
                                                Cancel
                                            </a>
                                            <button class="btn btn-primary" type="submit">
                                                Update
                                            </button>
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