<!doctype html>
<html lang="en">
    
    <head>
        @include('components.frontend.head')

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </head>

<body>

    <section class="header-wrap second-header">
        <div class="container-fluid text-center">
            <div class="header-img-box">
                <a href="{{ route( 'frontend.index' ) }}"><img src="{{ asset('frontend/assets/images/logo.webp') }}" class="img-responsive"></a>
                <h1>Cesspool Systems</h1>
            </div>
            <div class="header-back">
                <a href="{{ route( 'frontend.index' ) }}" class="btn"><i class="fa fa-long-arrow-left"></i> <span>Back to Home</span></a>
            </div>
        </div>
    </section>

    <section class="log-btn-wrap system-form-wrap">
        <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="systems-form-box">
                <div class="stepper text-center mb-4">
                    <div class="steps">
                        <span class="step active">Basic Information</span>
                        <span class="step">Site Observations</span>
                        <span class="step">System Evaluation</span>
                    </div> 
                    <div class="progress-container">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                </div>



                <form>
                    
                    <!-- Basic Info -->
                    <div class="form-step form-box active">
                        <div class="step-heading">
                        <h4>Basic Information</h4>
                        <p>Please provide basic detail of inspection</p>
                        </div>


                        <div class="row">
                        <div class="form-group col-md-12 text-center">
                            <label class="label-full">Type of Inspection</label>
                            <div class="center-field">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="home_inspection" value="Home Inspector" checked>
                                <label class="form-check-label" for="home_inspection">Home Inspection</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="realtor" value="Realtor">
                                <label class="form-check-label" for="realtor">Realtor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="routine" value="Routine Maintenance">
                                <label class="form-check-label" for="routine">Routine Maintenance</label>
                            </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Date of Inspection</label>
                            <div class="input-group">
                            <input type="text"
                                    id="date_of_pickup"
                                    class="form-control"
                                    placeholder="MM/DD/YYYY"
                                    required>
                            <span class="input-group-text custom-icon" id="calendar-icon-pickup">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Inspector Name & Company</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Site Address</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tax Map Number</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Type of System (DOH code if available)</label>
                            <input type="text" class="form-control">
                        </div>
                        </div>

                    </div>

                    <!-- ================= SITE OBSERVATIONS ================= -->
                    <div class="form-step form-box">
                        <div class="observations-box">
                        <div class="step-heading">
                            <h4>Site Observations</h4>
                            <p>Please record site conditions and observations during the inspection.</p>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label class="label-full">Property in use:</label>
                                <div class="form-checkbox-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="usee_yes">
                                    <label class="form-check-label" for="usee_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="usee_no">
                                    <label class="form-check-label" for="usee_no">No</label>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="form-group form-checkbox-group col-md-12">
                            <label>General Site Conditions:</label>

                            <div class="form-checkbox-group half-checkbox-group">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="grass">
                                <label class="form-check-label" for="grass">Grass cover/vegetation condition</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="system_area">
                                <label class="form-check-label" for="system_area">System area</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="other_area">
                                <label class="form-check-label" for="other_area">Other areas</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ponding">
                                <label class="form-check-label" for="ponding">Surface Ponding</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="barriers">
                                <label class="form-check-label" for="barriers">Protective Barriers Present</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="effective">
                                <label class="form-check-label" for="effective">Effective</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="not_effective">
                                <label class="form-check-label" for="not_effective">Not effective</label>
                                </div>
                            </div>
                            </div>
                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Surface runoff/gutters directed away from system:</label>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="runoff_yes">
                                <label class="form-check-label" for="runoff_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="runoff_no">
                                <label class="form-check-label" for="runoff_no">No</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="runoff_na">
                                <label class="form-check-label" for="runoff_na">N/A</label>
                                </div>
                            </div>
                            </div>
                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Malfunction at time of inspection:</label>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mal_yes">
                                <label class="form-check-label" for="mal_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mal_no">
                                <label class="form-check-label" for="mal_no">No</label>
                                </div>
                            </div>
                            </div>
                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Surface discharge via straight-pipe or damaged plumbing:</label>
                            </div>
                            <div class="form-checkbox-group half-checkbox-group">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="grey">
                                <label class="form-check-label" for="grey">Grey water</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="black">
                                <label class="form-check-label" for="black">Black water</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="unknown">
                                <label class="form-check-label" for="unknown">Unknown</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cesspool_area">
                                <label class="form-check-label" for="cesspool_area">Surface discharge in area of cesspool</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cesspool_edge">
                                <label class="form-check-label" for="cesspool_edge">Surface discharge at edge of cesspool area</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bleed_out">
                                <label class="form-check-label" for="bleed_out">Surface discharge - bleed-out away</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="past_failure">
                                <label class="form-check-label" for="past_failure">Evidence of past failure</label>
                                </div>
                              
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- ================= SYSTEM EVALUATION ================= -->
                    <div class="form-step form-box">
                        <div class="evaluation-box">
                        <div class="step-heading">
                            <h4>System Evaluation</h4>
                            <p>Please evaluate the system condition and note any issues or recommendations.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <h3 class="color-blue">Cesspool</h3>
                            </div>
                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Accessible Lids :</label>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="access_yes">
                                <label class="form-check-label" for="access_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="access_no">
                                <label class="form-check-label" for="access_no">No</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Access Lid(s) need repair:</label>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accesslid_yes">
                                <label class="form-check-label" for="accesslid_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accesslid_no">
                                <label class="form-check-label" for="accesslid_no">No</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group col-md-12">
                            <label>Cesspool Water Level Depth:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                                <label>Cesspool pumping recommended (sludge, scum and liquid occupy 50% or more of cesspool volume):</label>
                                <div class="form-checkbox-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pumping_yes">
                                    <label class="form-check-label" for="pumping_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pumping_no">
                                    <label class="form-check-label" for="pumping_no">No</label>
                                </div>
                                </div>
                            </div>
                            </div>

                            <div class="form-group col-md-6">
                            <label>Cesspool pumped of all liquids and solids:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                            <label>Water stream flowing into cesspool from house:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                            <label>Inlet pipe needs repair:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                            <label>Cesspool composition:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                            <label>Service recommended:</label>
                            <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                            <label><strong>Comments:</strong></label>
                            <textarea class="form-control"></textarea>
                            </div>

                            <!-- Disclaimer -->
                            <div class="form-group col-md-12">
                            <small>
                                <strong>Disclaimer:</strong> The above information indicates the conditions of the septic system at the time of inspection.
                                This is not a guarantee or warranty of future system performance.
                            </small>
                            </div>

                        </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Notes : </label>
                            <textarea class="form-control"></textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Inspector Signature:</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label><strong>Print Name:</strong></label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <label>Date</label>
                            <div class="input-group">
                            <input type="text"
                                    id="date"
                                    class="form-control"
                                    placeholder="MM/DD/YYYY"
                                    >
                            <span class="input-group-text custom-icon" id="calendar-icon-date">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            </div>
                        </div>

                        </div>
                    </div>


                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-secondary" id="prevBtn">Previous</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Save Draft</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                        <button type="submit" class="btn btn-success d-none" id="submitBtn">Submit</button>
                    </div>

                </form>


            </div>
            </div>
        </div>
        </div>
    </section>



    @include('components.frontend.footer')

    @include('components.frontend.main-js')

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".form-step");
        const stepIndicators = document.querySelectorAll(".step");
        const progressBar = document.getElementById("progressBar");

        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");
        const submitBtn = document.getElementById("submitBtn");

        function showStep(step) {
        steps.forEach((el, i) => {
            el.classList.toggle("active", i === step);
            stepIndicators[i].classList.toggle("active", i <= step);
        });

        // ✅ Progress calculation
        let progressPercent = ((step) / (steps.length - 1)) * 100;
        progressBar.style.width = progressPercent + "%";

        prevBtn.style.display = step === 0 ? "none" : "inline-block";
        nextBtn.style.display = step === steps.length - 1 ? "none" : "inline-block";
        submitBtn.classList.toggle("d-none", step !== steps.length - 1);
        }

        nextBtn.addEventListener("click", () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
        });

        prevBtn.addEventListener("click", () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
        });

        showStep(currentStep);
    </script>

    <!-----date picker js for the date columns--->
    <script>
        flatpickr("#date_of_pickup", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true
        });
        
        document.getElementById('calendar-icon-pickup').addEventListener('click', function () {
            document.getElementById('date_of_pickup')._flatpickr.open();
        });

        flatpickr("#date", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true
        });

        document.getElementById('calendar-icon-date').addEventListener('click', function () {
            document.getElementById('date')._flatpickr.open();
        });

    </script>
    
    <script>
        flatpickr("#date_of_discharge", {
            dateFormat: "m/d/Y",
            allowInput: true
        });
        
        document.getElementById('calendar-icon').addEventListener('click', function () {
            document.getElementById('date_of_discharge')._flatpickr.open();
        });
    </script>


</body>

</html>