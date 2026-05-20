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


                        <form id="cesspoolForm" action="{{ route('cesspool.store') }}" method="POST">
                            @csrf

                            <!-- Basic Info -->
                            <div class="form-step form-box active">
                                <div class="step-heading">
                                    <h4>Basic Information</h4>
                                    <p>Please provide basic detail of inspection</p>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 text-center">
                                        <label class="label-full">Type of Inspection <span class="text-danger">*</span></label>
                                        <div class="center-field">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="home_inspection" name="home_inspection" value="Home Inspector" checked>
                                                <label class="form-check-label" for="home_inspection">Home Inspection</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="realtor" name="realtor" value="Realtor">
                                                <label class="form-check-label" for="realtor">Realtor</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="routine" name="routine" value="Routine Maintenance">
                                                <label class="form-check-label" for="routine">Routine Maintenance</label>
                                            </div>
                                        </div>
                                        <div class="field-error text-danger" id="err_inspection" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Inspection <span class="text-danger">*</span></label>
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
                                        <div class="field-error text-danger" id="err_date_of_pickup" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Inspector Name & Company <span class="text-danger">*</span></label>
                                        <input type="text" id="inspector_name_company" name="inspector_name_company" class="form-control">
                                        <div class="field-error text-danger" id="err_inspector_name_company" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Site Address <span class="text-danger">*</span></label>
                                        <input type="text" id="site_address" name="site_address" class="form-control">
                                        <div class="field-error text-danger" id="err_site_address" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tax Map Number <span class="text-danger">*</span></label>
                                        <input type="text" id="tax_map_number" name="tax_map_number" class="form-control">
                                        <div class="field-error text-danger" id="err_tax_map_number" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Type of System (DOH code if available) <span class="text-danger">*</span></label>
                                        <input type="text" id="type_of_system" name="type_of_system" class="form-control">
                                        <div class="field-error text-danger" id="err_type_of_system" style="display:none;font-size:15px;margin-top:4px;"></div>
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
                                                <label class="label-full">Property in use: <span class="text-danger">*</span></label>

                                                <div class="form-checkbox-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="usee_yes" id="usee_yes">
                                                        <label class="form-check-label" for="usee_yes">Yes</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="usee_no" id="usee_no">
                                                        <label class="form-check-label" for="usee_no">No</label>
                                                    </div>
                                                </div>
                                                <div class="field-error text-danger" id="err_property_use" style="display:none;font-size:15px;margin-top:4px;"></div>
                                            </div>
                                        </div>

                                        <div class="form-group form-checkbox-group col-md-12">
                                            <label>General Site Conditions: <span class="text-danger">*</span></label>

                                            <div class="form-checkbox-group half-checkbox-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="grass" name="grass">
                                                    <label class="form-check-label" for="grass">Grass cover/vegetation condition</label>
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
                                                    <input class="form-check-input" type="checkbox" id="ponding" name="ponding">
                                                    <label class="form-check-label" for="ponding">Surface Ponding</label>
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
                                            <div class="field-error text-danger" id="err_site_conditions" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>


                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Surface runoff/gutters directed away from system: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_runoff" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Malfunction at time of inspection: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mal_yes" name="mal_yes">
                                                    <label class="form-check-label" for="mal_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mal_no" name="mal_no">
                                                    <label class="form-check-label" for="mal_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_malfunction" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Surface discharge via straight-pipe or damaged plumbing: <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="form-checkbox-group half-checkbox-group">
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
                                                    <input class="form-check-input" type="checkbox" id="cesspool_area" name="cesspool_area">
                                                    <label class="form-check-label" for="cesspool_area">Surface discharge in area of cesspool</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="cesspool_edge" name="cesspool_edge">
                                                    <label class="form-check-label" for="cesspool_edge">Surface discharge at edge of cesspool area</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="bleed_out" name="bleed_out">
                                                    <label class="form-check-label" for="bleed_out">Surface discharge - bleed-out away</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="past_failure" name="past_failure">
                                                    <label class="form-check-label" for="past_failure">Evidence of past failure</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_surface_discharge" style="display:none;font-size:15px;margin-top:4px;"></div>
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
                                                <label>Accessible Lids : <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="access_yes" name="access_yes">
                                                    <label class="form-check-label" for="access_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="access_no" name="access_no">
                                                    <label class="form-check-label" for="access_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_access_lids" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Access Lid(s) need repair: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="accesslid_yes" name="accesslid_yes">
                                                    <label class="form-check-label" for="accesslid_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="accesslid_no" name="accesslid_no">
                                                    <label class="form-check-label" for="accesslid_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_accesslid" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Cesspool Water Level Depth: <span class="text-danger">*</span></label>
                                            <input type="text" id="cesspool_water_level_depth" name="cesspool_water_level_depth" class="form-control">
                                            <div class="field-error text-danger" id="err_cesspool_water_level_depth" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Cesspool pumping recommended (sludge, scum and liquid occupy 50% or more of cesspool volume): <span class="text-danger">*</span></label>
                                                <div class="form-checkbox-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="pumping_yes" name="pumping_yes">
                                                        <label class="form-check-label" for="pumping_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="pumping_no" name="pumping_no">
                                                        <label class="form-check-label" for="pumping_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_pumping" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Cesspool pumped of all liquids and solids: <span class="text-danger">*</span></label>
                                            <input type="text" id="cesspool_pumped" name="cesspool_pumped" class="form-control">
                                            <div class="field-error text-danger" id="err_cesspool_pumped" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Water stream flowing into cesspool from house: <span class="text-danger">*</span></label>
                                            <input type="text" id="water_stream_from_house" name="water_stream_from_house" class="form-control">
                                            <div class="field-error text-danger" id="err_water_stream_from_house" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Inlet pipe needs repair: <span class="text-danger">*</span></label>
                                            <input type="text" id="inlet_pipe_needs_repair" name="inlet_pipe_needs_repair" class="form-control">
                                            <div class="field-error text-danger" id="err_inlet_pipe_needs_repair" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Cesspool composition: <span class="text-danger">*</span></label>
                                            <input type="text" id="cesspool_composition" name="cesspool_composition" class="form-control">
                                            <div class="field-error text-danger" id="err_cesspool_composition" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Service recommended: <span class="text-danger">*</span></label>
                                            <input type="text" id="service_recommended" name="service_recommended" class="form-control">
                                            <div class="field-error text-danger" id="err_service_recommended" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label><strong>Comments:</strong> <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="comments" name="comments"></textarea>
                                            <div class="field-error text-danger" id="err_comments" style="display:none;font-size:15px;margin-top:4px;"></div>
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
                                    <label>Notes : <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="notes" name="notes"></textarea>
                                    <div class="field-error text-danger" id="err_notes" style="display:none;font-size:15px;margin-top:4px;"></div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Inspector Signature: <span class="text-danger">*</span></label>
                                        <input type="text" id="inspector_signature" name="inspector_signature" class="form-control">
                                        <div class="field-error text-danger" id="err_inspector_signature" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label><strong>Print Name:</strong> <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="print_name" name="print_name"></textarea>
                                        <div class="field-error text-danger" id="err_print_name" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text"
                                            id="date"
                                            name="date"
                                            class="form-control"
                                            placeholder="MM/DD/YYYY">
                                        <span class="input-group-text custom-icon" id="calendar-icon-date">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </span>
                                    </div>
                                    <div class="field-error text-danger" id="err_date" style="display:none;font-size:15px;margin-top:4px;"></div>
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

        function showError(id, message) {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = message;
                el.style.display = 'block';
            }
        }

        function clearErrors(stepIndex) {
            steps[stepIndex].querySelectorAll('.field-error').forEach(function(el) {
                el.style.display = 'none';
                el.textContent = '';
            });
            steps[stepIndex].querySelectorAll('.form-control').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
        }

        function validateStep(step) {
            clearErrors(step);
            let isValid = true;

            if (step === 0) {
                // Type of Inspection
                if (!document.getElementById('home_inspection').checked &&
                    !document.getElementById('realtor').checked &&
                    !document.getElementById('routine').checked) {
                    showError('err_inspection', 'Please select at least one type of inspection.');
                    isValid = false;
                }

                // Date of Inspection
                const dateVal = document.getElementById('date_of_pickup').value.trim();
                if (!dateVal) {
                    showError('err_date_of_pickup', 'Date of Inspection is required.');
                    document.getElementById('date_of_pickup').classList.add('is-invalid');
                    isValid = false;
                }

                // Inspector Name & Company
                const inspectorName = document.getElementById('inspector_name_company').value.trim();
                if (!inspectorName) {
                    showError('err_inspector_name_company', 'Inspector Name & Company is required.');
                    document.getElementById('inspector_name_company').classList.add('is-invalid');
                    isValid = false;
                } else if (/\d/.test(inspectorName)) {
                    showError('err_inspector_name_company', 'Inspector Name & Company cannot contain numbers.');
                    document.getElementById('inspector_name_company').classList.add('is-invalid');
                    isValid = false;
                }

                // Site Address
                const siteAddress = document.getElementById('site_address').value.trim();
                if (!siteAddress) {
                    showError('err_site_address', 'Site Address is required.');
                    document.getElementById('site_address').classList.add('is-invalid');
                    isValid = false;
                }

                // Tax Map Number
                const taxMap = document.getElementById('tax_map_number').value.trim();
                if (!taxMap) {
                    showError('err_tax_map_number', 'Tax Map Number is required.');
                    document.getElementById('tax_map_number').classList.add('is-invalid');
                    isValid = false;
                }

                // Type of System
                const typeSystem = document.getElementById('type_of_system').value.trim();
                if (!typeSystem) {
                    showError('err_type_of_system', 'Type of System is required.');
                    document.getElementById('type_of_system').classList.add('is-invalid');
                    isValid = false;
                }

            } else if (step === 1) {
                // Property in use
                if (!document.getElementById('usee_yes').checked && !document.getElementById('usee_no').checked) {
                    showError('err_property_use', 'Please select if the property is in use.');
                    isValid = false;
                }

                // General Site Conditions
                const siteConditionIds = ['grass', 'system_area', 'other_area', 'ponding', 'barriers', 'effective', 'not_effective'];
                if (!siteConditionIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_site_conditions', 'Please select at least one General Site Condition.');
                    isValid = false;
                }

                // Surface runoff
                if (!document.getElementById('runoff_yes').checked &&
                    !document.getElementById('runoff_no').checked &&
                    !document.getElementById('runoff_na').checked) {
                    showError('err_runoff', 'Please select an option for Surface runoff.');
                    isValid = false;
                }

                // Malfunction
                if (!document.getElementById('mal_yes').checked && !document.getElementById('mal_no').checked) {
                    showError('err_malfunction', 'Please select an option for Malfunction at time of inspection.');
                    isValid = false;
                }

                // Surface discharge
                const dischargeIds = ['grey', 'black', 'unknown', 'cesspool_area', 'cesspool_edge', 'bleed_out', 'past_failure'];
                if (!dischargeIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_surface_discharge', 'Please select at least one Surface discharge option.');
                    isValid = false;
                }

            } else if (step === 2) {
                // Accessible Lids
                if (!document.getElementById('access_yes').checked && !document.getElementById('access_no').checked) {
                    showError('err_access_lids', 'Please select an option for Accessible Lids.');
                    isValid = false;
                }

                // Access Lid(s) need repair
                if (!document.getElementById('accesslid_yes').checked && !document.getElementById('accesslid_no').checked) {
                    showError('err_accesslid', 'Please select an option for Access Lid(s) need repair.');
                    isValid = false;
                }

                // Cesspool Water Level Depth
                const waterLevel = document.getElementById('cesspool_water_level_depth').value.trim();
                if (!waterLevel) {
                    showError('err_cesspool_water_level_depth', 'Cesspool Water Level Depth is required.');
                    document.getElementById('cesspool_water_level_depth').classList.add('is-invalid');
                    isValid = false;
                }

                // Cesspool pumping recommended
                if (!document.getElementById('pumping_yes').checked && !document.getElementById('pumping_no').checked) {
                    showError('err_pumping', 'Please select an option for Cesspool pumping recommended.');
                    isValid = false;
                }

                // Cesspool pumped
                const cesspoolPumped = document.getElementById('cesspool_pumped').value.trim();
                if (!cesspoolPumped) {
                    showError('err_cesspool_pumped', 'This field is required.');
                    document.getElementById('cesspool_pumped').classList.add('is-invalid');
                    isValid = false;
                }

                // Water stream from house
                const waterStream = document.getElementById('water_stream_from_house').value.trim();
                if (!waterStream) {
                    showError('err_water_stream_from_house', 'This field is required.');
                    document.getElementById('water_stream_from_house').classList.add('is-invalid');
                    isValid = false;
                }

                // Inlet pipe
                const inletPipe = document.getElementById('inlet_pipe_needs_repair').value.trim();
                if (!inletPipe) {
                    showError('err_inlet_pipe_needs_repair', 'This field is required.');
                    document.getElementById('inlet_pipe_needs_repair').classList.add('is-invalid');
                    isValid = false;
                }

                // Cesspool composition
                const cesspoolComp = document.getElementById('cesspool_composition').value.trim();
                if (!cesspoolComp) {
                    showError('err_cesspool_composition', 'Cesspool composition is required.');
                    document.getElementById('cesspool_composition').classList.add('is-invalid');
                    isValid = false;
                }

                // Service recommended
                const serviceRec = document.getElementById('service_recommended').value.trim();
                if (!serviceRec) {
                    showError('err_service_recommended', 'Service recommended is required.');
                    document.getElementById('service_recommended').classList.add('is-invalid');
                    isValid = false;
                }

                // Comments
                const comments = document.getElementById('comments').value.trim();
                if (!comments) {
                    showError('err_comments', 'Comments are required.');
                    document.getElementById('comments').classList.add('is-invalid');
                    isValid = false;
                }

                // Notes
                const notes = document.getElementById('notes').value.trim();
                if (!notes) {
                    showError('err_notes', 'Notes are required.');
                    document.getElementById('notes').classList.add('is-invalid');
                    isValid = false;
                }

                // Inspector Signature
                const inspSig = document.getElementById('inspector_signature').value.trim();
                if (!inspSig) {
                    showError('err_inspector_signature', 'Inspector Signature is required.');
                    document.getElementById('inspector_signature').classList.add('is-invalid');
                    isValid = false;
                } else if (/\d/.test(inspSig)) {
                    showError('err_inspector_signature', 'Inspector Signature cannot contain numbers.');
                    document.getElementById('inspector_signature').classList.add('is-invalid');
                    isValid = false;
                }

                // Print Name
                const printName = document.getElementById('print_name').value.trim();
                if (!printName) {
                    showError('err_print_name', 'Print Name is required.');
                    document.getElementById('print_name').classList.add('is-invalid');
                    isValid = false;
                } else if (/\d/.test(printName)) {
                    showError('err_print_name', 'Print Name cannot contain numbers.');
                    document.getElementById('print_name').classList.add('is-invalid');
                    isValid = false;
                }

                // Date
                const dateVal = document.getElementById('date').value.trim();
                if (!dateVal) {
                    showError('err_date', 'Date is required.');
                    document.getElementById('date').classList.add('is-invalid');
                    isValid = false;
                }
            }

            return isValid;
        }

        nextBtn.addEventListener("click", function() {
            if (!validateStep(currentStep)) return;
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });

        prevBtn.addEventListener("click", function() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        document.getElementById('cesspoolForm').addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
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

        document.getElementById('calendar-icon-pickup').addEventListener('click', function() {
            document.getElementById('date_of_pickup')._flatpickr.open();
        });

        flatpickr("#date", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true
        });

        document.getElementById('calendar-icon-date').addEventListener('click', function() {
            document.getElementById('date')._flatpickr.open();
        });
    </script>

    <script>
        flatpickr("#date_of_discharge", {
            dateFormat: "m/d/Y",
            allowInput: true
        });

        document.getElementById('calendar-icon').addEventListener('click', function() {
            document.getElementById('date_of_discharge')._flatpickr.open();
        });
    </script>


</body>

</html>
