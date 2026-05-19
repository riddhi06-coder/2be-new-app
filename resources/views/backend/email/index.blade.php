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
                                    <a href="{{ route('manage-email-settings.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Email Details
                                </li>
                            </ol>
                        </nav>
                        <a href="{{ route('manage-email-settings.create') }}" class="btn btn-primary px-5 radius-30">
                            + Add Emails
                        </a>
                    </div>


                    <div class="table-responsive custom-scrollbar mt-5">
                    
                        <table class="display" id="basic-1">
                        
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Default Email</th>
                                    <th>Email 1</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emails as $key => $email)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $email->default_email }}</td>
                                            <td>{{ $email->email1 }}</td>
                                            <td>
                                                <a href="{{ route('manage-email-settings.edit', $email->id) }}" 
                                                class="btn btn-sm btn-primary">
                                                    Edit
                                                </a>

                                                <form action="{{ route('manage-email-settings.destroy', $email->id) }}" 
                                                    method="POST" 
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No email settings found.
                                            </td>
                                        </tr>
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
            <!-- footer start-->
             @include('components.backend.footer')
      </div>
    </div>
    

        @include('components.backend.main-js')

</body>

</html>