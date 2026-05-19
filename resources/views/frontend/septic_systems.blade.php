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
            <a href="{{ route( 'frontend.index' ) }}"><img src="{{ asset('frontend/assets/images/logo.webp' ) }}" class="img-responsive"></a>
            <h1>Septic Systems</h1>
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
                        <!-- Type of Inspection -->
                        <div class="form-group col-md-12 text-center">
                        <label>Type of Inspection</label>
                        <div class="center-field">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="home_inspection" name="home_inspection" value="Home Inspector" checked>
                                <label class="form-check-label" for="home_inspection">Home Inspection</label>
                            </div>

                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="realtor" name="realtor" value="Realtor">
                            <label class="form-check-label" for="realtor">Realtor</label>
                            </div>
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="routine" name="routine" value="Routine Maintenance">
                            <label class="form-check-label" for="routine">Routine Maintenance</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group col-md-4">
                        <label>Date of Inspection</label>
                        <div class="input-group">
                            <input type="text"
                                id="date_of_pickup"
                                name="date_of_pickup"
                                class="form-control"
                                placeholder="MM/DD/YYYY"
                                required>
                            <span class="input-group-text custom-icon" id="calendar-icon-pickup">
                            <i class="fa-solid fa-calendar-days"></i>
                            </span>
                        </div>
                        </div>

                        <div class="form-group col-md-4">
                        <label>Time</label>
                        <input type="text" id="time" name="time" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                        <label>Weather</label>
                        <input type="text" id="weather" name="weather" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                        <label>Inspector Name & Company</label>
                        <input type="text" id="inspector_name_company" name="inspector_name_company" class="form-control" value="">
                        </div>

                        <div class="form-group col-md-12">
                        <label>Site Address</label>
                        <textarea class="form-control" id="site_address" name="site_address"></textarea>
                        </div>

                        <div class="form-group col-md-6">
                        <label>Tax Map Number</label>
                        <input type="text" id="tax_map_number" name="tax_map_number" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                        <label>Type of System (DOH code if available)</label>
                        <input type="text" id="type_of_system" name="type_of_system" class="form-control">
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

                        <div class="form-group col-md-12">
                        <label>Property in use:</label>
                        <div class="form-checkbox-group half-checkbox-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_yes" name="use_yes">
                            <label class="form-check-label" for="use_yes">Yes</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_no" name="use_no">
                            <label class="form-check-label" for="use_no">No</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_fulltime" name="use_fulltime">
                            <label class="form-check-label" for="use_fulltime">Full time</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_vacation" name="use_vacation">
                            <label class="form-check-label" for="use_vacation">Vacation Rental</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_vacant" name="use_vacant">
                            <label class="form-check-label" for="use_vacant">Vacant</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_other" name="use_other">
                            <label class="form-check-label" for="use_other">Other</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_unknown" name="use_unknown">
                            <label class="form-check-label" for="use_unknown">Unknown</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group col-md-12">
                        <label>General Site Conditions:</label>
                            <div class="form-checkbox-group half-checkbox-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="grass" name="grass">
                            <label class="form-check-label" for="grass">Grass cover/vegetation condition</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cinder" name="cinder">
                            <label class="form-check-label" for="cinder">Cinder/rocks</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ponding" name="ponding">
                            <label class="form-check-label" for="ponding">Surface Ponding</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="system_area" name="system_area">
                            <label class="form-check-label" for="system_area">System area</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="other_area" name="other_area">
                            <label class="form-check-label" for="other_area">Other areas</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="barriers" name="barriers">
                            <label class="form-check-label" for="barriers">Protective Barriers Present</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="effective" name="effective">
                            <label class="form-check-label" for="effective">Effective</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="not_effective" name="not_effective">
                            <label class="form-check-label" for="not_effective">Not effective</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group col-md-12">
                        <label>Surface runoff/gutters directed away from system :</label>
                        <div class="form-checkbox-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="runoff_yes" name="runoff_yes">
                            <label class="form-check-label" for="runoff_yes">Yes</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="runoff_no" name="runoff_no">
                            <label class="form-check-label" for="runoff_no">No</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="runoff_na" name="runoff_na">
                            <label class="form-check-label" for="runoff_na">N/A</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group col-md-12 mB0">
                        <label>Malfunction at time of inspection:</label>
                            <div class="form-checkbox-group half-checkbox-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mal_yes" name="mal_yes">
                            <label class="form-check-label" for="mal_yes">Yes</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mal_no" name="mal_no">
                            <label class="form-check-label" for="mal_no">No</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="surface_plumbing" name="surface_plumbing">
                            <label class="form-check-label" for="surface_plumbing">Surface discharge via plumbing</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="grey" name="grey">
                            <label class="form-check-label" for="grey">Grey water</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="black" name="black">
                            <label class="form-check-label" for="black">Black water</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="unknown" name="unknown">
                            <label class="form-check-label" for="unknown">Unknown</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tank_area" name="tank_area">
                            <label class="form-check-label" for="tank_area">Surface discharge in area of tank</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tile_field" name="tile_field">
                            <label class="form-check-label" for="tile_field">Surface discharge within tile field area</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edge_field" name="edge_field">
                            <label class="form-check-label" for="edge_field">Surface discharge at edge of tile field</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bleed_out" name="bleed_out">
                            <label class="form-check-label" for="bleed_out">Surface discharge bleed-out away from system</label>
                            </div>

                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="past_failure" name="past_failure">
                            <label class="form-check-label" for="past_failure">Evidence of past failure / Note evidence</label>
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
                            <h3 class="color-blue">Manhole covers</h3>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Accessible:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accessible_yes" name="accessible_yes">
                                <label class="form-check-label" for="accessible_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accessible_no" name="accessible_no">
                                <label class="form-check-label" for="accessible_no">No</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Lid(s) need repair:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lid_yes" name="lid_yes">
                                <label class="form-check-label" for="lid_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lid_no" name="lid_no">
                                <label class="form-check-label" for="lid_no">No</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Liquid operating level:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="level_outlet" name="level_outlet">
                                <label class="form-check-label" for="level_outlet">At outlet invert</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="level_above" name="level_above">
                                <label class="form-check-label" for="level_above">Above outlet invert</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="level_below" name="level_below">
                                <label class="form-check-label" for="level_below">Below outlet invert</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Scum layer thickness (in.):</label>
                            <input type="text" id="scum_layer_thickness" name="scum_layer_thickness" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Sludge layer thickness (in.):</label>
                            <input type="text" id="sludge_layer_thickness" name="sludge_layer_thickness" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Tank pumping recommended (sludge plus scum occupy 25% or more of tank volume):</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pump_yes" name="pump_yes">
                                <label class="form-check-label" for="pump_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pump_no" name="pump_no">
                                <label class="form-check-label" for="pump_no">No</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Tank pumped of all liquids and solids:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pumped_yes" name="pumped_yes">
                                <label class="form-check-label" for="pumped_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pumped_no" name="pumped_no">
                                <label class="form-check-label" for="pumped_no">No</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pumped_na" name="pumped_na">
                                <label class="form-check-label" for="pumped_na">N/A</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="form-checkbox-group">
                            <label>Approx. volume pumped (gals):</label>
                            <input type="text" id="approx_volume_pumped" name="approx_volume_pumped" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Water stream into tank from house:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="house_yes" name="house_yes">
                                <label class="form-check-label" for="house_yes">Yes</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="house_trickle" name="house_trickle">
                                <label class="form-check-label" for="house_trickle">Trickle</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="house_steady" name="house_steady">
                                <label class="form-check-label" for="house_steady">Steady flow</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="house_no" name="house_no">
                                <label class="form-check-label" for="house_no">No</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="house_na" name="house_na">
                                <label class="form-check-label" for="house_na">N/A</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Water stream into tank from drain field:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="drain_yes" name="drain_yes">
                                <label class="form-check-label" for="drain_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="drain_trickle" name="drain_trickle">
                                <label class="form-check-label" for="drain_trickle">Trickle</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="drain_steady" name="drain_steady">
                                <label class="form-check-label" for="drain_steady">Steady flow</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="drain_no" name="drain_no">
                                <label class="form-check-label" for="drain_no">No</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="drain_na" name="drain_na">
                                <label class="form-check-label" for="drain_na">N/A</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Inlet tee needs repair:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="inlet_yes" name="inlet_yes">
                                <label class="form-check-label" for="inlet_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="inlet_nd" name="inlet_nd">
                                <label class="form-check-label" for="inlet_nd">N/D</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Outlet tee needs repair:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="outlet_yes" name="outlet_yes">
                                <label class="form-check-label" for="outlet_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="outlet_nd" name="outlet_nd">
                                <label class="form-check-label" for="outlet_nd">N/D</label>
                            </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Tank composition:</label>
                            <input type="text" id="tank_composition" name="tank_composition" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Approx. size of tank (gals):</label>
                            <input type="text" id="approx_tank_size" name="approx_tank_size" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-checkbox-group">
                            <label>Service recommended:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="service_yes" name="service_yes">
                                <label class="form-check-label" for="service_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="service_no" name="service_no">
                                <label class="form-check-label" for="service_no">No</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="service_nd" name="service_nd">
                                <label class="form-check-label" for="service_nd">N/D</label>
                            </div>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="form-group col-md-12">
                            <label>Comments:</label>
                            <textarea class="form-control" id="comments" name="comments"></textarea>
                        </div>

                        <!-- Signature -->
                        <div class="form-group col-md-12">
                            <label>Inspector Signature:</label>
                            <input type="text" id="inspector_signature" name="inspector_signature" class="form-control">
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
                    <div class="graph-box">
                        <div class="form-group col-md-12">
                        <label>Notes : </label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
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

    <script>
        flatpickr("#date_of_pickup", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true
        });

        document.getElementById('calendar-icon').addEventListener('click', function () {
            document.getElementById('date_of_pickup')._flatpickr.open();
        });
    </script>

    <script>
        flatpickr("#date_of_discharge", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true
        });

        document.getElementById('calendar-icon').addEventListener('click', function () {
            document.getElementById('date_of_discharge')._flatpickr.open();
        });
    </script>


</body>

</html>
