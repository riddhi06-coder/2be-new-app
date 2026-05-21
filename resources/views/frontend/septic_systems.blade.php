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

                        <form id="septicForm" action="{{ route('septic.store') }}" method="POST">
                            @csrf

                            <!-- ================= STEP 1: BASIC INFORMATION ================= -->
                            <div class="form-step form-box active">
                                <div class="step-heading">
                                    <h4>Basic Information</h4>
                                    <p>Please provide basic detail of inspection</p>
                                </div>
                                <div class="row">

                                    <!-- Type of Inspection -->
                                    <div class="form-group col-md-12 text-center">
                                        <label class="label-full">Type of Inspection <span class="text-danger">*</span></label>
                                        <div class="center-field">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="home_inspection" name="home_inspection" value="Home Inspector">
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
                                        <div class="field-error text-danger" id="err_inspection" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Date of Inspection <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text"
                                                id="date_of_pickup"
                                                name="date_of_pickup"
                                                class="form-control"
                                                placeholder="MM/DD/YYYY">
                                            <span class="input-group-text custom-icon" id="calendar-icon-pickup">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                        </div>
                                        <div class="field-error text-danger" id="err_date_of_pickup" style="display:none;font-size:15px;margin-top:4px;"></div>
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
                                        <label>Inspector Name & Company <span class="text-danger">*</span></label>
                                        <input type="text" id="inspector_name_company" name="inspector_name_company" class="form-control">
                                        <div class="field-error text-danger" id="err_inspector_name_company" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Site Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="site_address" name="site_address"></textarea>
                                        <div class="field-error text-danger" id="err_site_address" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Tax Map Number <span class="text-danger">*</span></label>
                                        <input type="text" id="tax_map_number" name="tax_map_number" class="form-control">
                                        <div class="field-error text-danger" id="err_tax_map_number" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Type of System (DOH code if available) <span class="text-danger">*</span></label>
                                        <input type="text" id="type_of_system" name="type_of_system" class="form-control">
                                        <div class="field-error text-danger" id="err_type_of_system" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                </div>
                            </div>

                            <!-- ================= STEP 2: SITE OBSERVATIONS ================= -->
                            <div class="form-step form-box">
                                <div class="observations-box">
                                    <div class="step-heading">
                                        <h4>Site Observations</h4>
                                        <p>Please record site conditions and observations during the inspection.</p>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Property in use: <span class="text-danger">*</span></label>
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
                                        <div class="field-error text-danger" id="err_property_use" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>General Site Conditions: <span class="text-danger">*</span></label>
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
                                        <div class="field-error text-danger" id="err_site_conditions" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Surface runoff/gutters directed away from system: <span class="text-danger">*</span></label>
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
                                        <div class="field-error text-danger" id="err_runoff" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                    <div class="form-group col-md-12 mB0">
                                        <label>Malfunction at time of inspection: <span class="text-danger">*</span></label>
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
                                        <div class="field-error text-danger" id="err_malfunction" style="display:none;font-size:15px;margin-top:4px;"></div>
                                    </div>

                                </div>
                            </div>

                            <!-- ================= STEP 3: SYSTEM EVALUATION ================= -->
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
                                                <label>Accessible: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="accessible_yes" name="accessible_yes">
                                                    <label class="form-check-label" for="accessible_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="accessible_no" name="accessible_no">
                                                    <label class="form-check-label" for="accessible_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_accessible" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Lid(s) need repair: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="lid_yes" name="lid_yes">
                                                    <label class="form-check-label" for="lid_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="lid_no" name="lid_no">
                                                    <label class="form-check-label" for="lid_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_lid_repair" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Liquid operating level: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_liquid_level" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Scum layer thickness (in.): <span class="text-danger">*</span></label>
                                            <input type="text" id="scum_layer_thickness" name="scum_layer_thickness" class="form-control">
                                            <div class="field-error text-danger" id="err_scum" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Sludge layer thickness (in.): <span class="text-danger">*</span></label>
                                            <input type="text" id="sludge_layer_thickness" name="sludge_layer_thickness" class="form-control">
                                            <div class="field-error text-danger" id="err_sludge" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Tank pumping recommended (sludge plus scum occupy 25% or more of tank volume): <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pump_yes" name="pump_yes">
                                                    <label class="form-check-label" for="pump_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pump_no" name="pump_no">
                                                    <label class="form-check-label" for="pump_no">No</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_pump_recommended" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Tank pumped of all liquids and solids: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_tank_pumped" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Approx. volume pumped (gals): <span class="text-danger">*</span></label>
                                            <input type="text" id="approx_volume_pumped" name="approx_volume_pumped" class="form-control">
                                            <div class="field-error text-danger" id="err_approx_volume" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Water stream into tank from house: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_house_stream" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Water stream into tank from drain field: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_drain_stream" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Inlet tee needs repair: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="inlet_yes" name="inlet_yes">
                                                    <label class="form-check-label" for="inlet_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="inlet_nd" name="inlet_nd">
                                                    <label class="form-check-label" for="inlet_nd">N/D</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_inlet_tee" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Outlet tee needs repair: <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="outlet_yes" name="outlet_yes">
                                                    <label class="form-check-label" for="outlet_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="outlet_nd" name="outlet_nd">
                                                    <label class="form-check-label" for="outlet_nd">N/D</label>
                                                </div>
                                            </div>
                                            <div class="field-error text-danger" id="err_outlet_tee" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Tank composition: <span class="text-danger">*</span></label>
                                            <input type="text" id="tank_composition" name="tank_composition" class="form-control">
                                            <div class="field-error text-danger" id="err_tank_composition" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Approx. size of tank (gals): <span class="text-danger">*</span></label>
                                            <input type="text" id="approx_tank_size" name="approx_tank_size" class="form-control">
                                            <div class="field-error text-danger" id="err_approx_tank_size" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="form-checkbox-group">
                                                <label>Service recommended: <span class="text-danger">*</span></label>
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
                                            <div class="field-error text-danger" id="err_service" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label><strong>Comments:</strong> <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="comments" name="comments"></textarea>
                                            <div class="field-error text-danger" id="err_comments" style="display:none;font-size:15px;margin-top:4px;"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Inspector Signature: <span class="text-danger">*</span></label>
                                            <input type="text" id="inspector_signature" name="inspector_signature" class="form-control">
                                            <div class="field-error text-danger" id="err_inspector_signature" style="display:none;font-size:15px;margin-top:4px;"></div>
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
                                    <label>Notes: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="notes" name="notes"></textarea>
                                    <div class="field-error text-danger" id="err_notes" style="display:none;font-size:15px;margin-top:4px;"></div>
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


    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;flex-direction:column;">
        <div class="spinner-border text-light" role="status" style="width:3.5rem;height:3.5rem;border-width:4px;"></div>
        <p id="loadingText" style="color:#fff;margin-top:20px;font-size:18px;font-weight:600;letter-spacing:0.5px;">Please wait...</p>
    </div>

    @include('components.frontend.footer')

    @include('components.frontend.main-js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                const propertyIds = ['use_yes','use_no','use_fulltime','use_vacation','use_vacant','use_other','use_unknown'];
                if (!propertyIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_property_use', 'Please select at least one option for Property in use.');
                    isValid = false;
                }

                // General Site Conditions
                const siteCondIds = ['grass','cinder','ponding','system_area','other_area','barriers','effective','not_effective'];
                if (!siteCondIds.some(function(id) { return document.getElementById(id).checked; })) {
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
                const malfunctionIds = ['mal_yes','mal_no','surface_plumbing','grey','black','unknown','tank_area','tile_field','edge_field','bleed_out','past_failure'];
                if (!malfunctionIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_malfunction', 'Please select at least one option for Malfunction.');
                    isValid = false;
                }

            } else if (step === 2) {
                // Accessible
                if (!document.getElementById('accessible_yes').checked && !document.getElementById('accessible_no').checked) {
                    showError('err_accessible', 'Please select an option for Accessible.');
                    isValid = false;
                }

                // Lid(s) need repair
                if (!document.getElementById('lid_yes').checked && !document.getElementById('lid_no').checked) {
                    showError('err_lid_repair', 'Please select an option for Lid(s) need repair.');
                    isValid = false;
                }

                // Liquid operating level
                if (!document.getElementById('level_outlet').checked &&
                    !document.getElementById('level_above').checked &&
                    !document.getElementById('level_below').checked) {
                    showError('err_liquid_level', 'Please select an option for Liquid operating level.');
                    isValid = false;
                }

                // Scum layer thickness
                const scum = document.getElementById('scum_layer_thickness').value.trim();
                if (!scum) {
                    showError('err_scum', 'Scum layer thickness is required.');
                    document.getElementById('scum_layer_thickness').classList.add('is-invalid');
                    isValid = false;
                }

                // Sludge layer thickness
                const sludge = document.getElementById('sludge_layer_thickness').value.trim();
                if (!sludge) {
                    showError('err_sludge', 'Sludge layer thickness is required.');
                    document.getElementById('sludge_layer_thickness').classList.add('is-invalid');
                    isValid = false;
                }

                // Tank pumping recommended
                if (!document.getElementById('pump_yes').checked && !document.getElementById('pump_no').checked) {
                    showError('err_pump_recommended', 'Please select an option for Tank pumping recommended.');
                    isValid = false;
                }

                // Tank pumped
                if (!document.getElementById('pumped_yes').checked &&
                    !document.getElementById('pumped_no').checked &&
                    !document.getElementById('pumped_na').checked) {
                    showError('err_tank_pumped', 'Please select an option for Tank pumped.');
                    isValid = false;
                }

                // Approx. volume pumped
                const approxVol = document.getElementById('approx_volume_pumped').value.trim();
                if (!approxVol) {
                    showError('err_approx_volume', 'Approx. volume pumped is required.');
                    document.getElementById('approx_volume_pumped').classList.add('is-invalid');
                    isValid = false;
                }

                // Water stream from house
                const houseIds = ['house_yes','house_trickle','house_steady','house_no','house_na'];
                if (!houseIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_house_stream', 'Please select an option for Water stream from house.');
                    isValid = false;
                }

                // Water stream from drain field
                const drainIds = ['drain_yes','drain_trickle','drain_steady','drain_no','drain_na'];
                if (!drainIds.some(function(id) { return document.getElementById(id).checked; })) {
                    showError('err_drain_stream', 'Please select an option for Water stream from drain field.');
                    isValid = false;
                }

                // Inlet tee
                if (!document.getElementById('inlet_yes').checked && !document.getElementById('inlet_nd').checked) {
                    showError('err_inlet_tee', 'Please select an option for Inlet tee needs repair.');
                    isValid = false;
                }

                // Outlet tee
                if (!document.getElementById('outlet_yes').checked && !document.getElementById('outlet_nd').checked) {
                    showError('err_outlet_tee', 'Please select an option for Outlet tee needs repair.');
                    isValid = false;
                }

                // Tank composition
                const tankComp = document.getElementById('tank_composition').value.trim();
                if (!tankComp) {
                    showError('err_tank_composition', 'Tank composition is required.');
                    document.getElementById('tank_composition').classList.add('is-invalid');
                    isValid = false;
                }

                // Approx. size of tank
                const tankSize = document.getElementById('approx_tank_size').value.trim();
                if (!tankSize) {
                    showError('err_approx_tank_size', 'Approx. size of tank is required.');
                    document.getElementById('approx_tank_size').classList.add('is-invalid');
                    isValid = false;
                }

                // Service recommended
                if (!document.getElementById('service_yes').checked &&
                    !document.getElementById('service_no').checked &&
                    !document.getElementById('service_nd').checked) {
                    showError('err_service', 'Please select an option for Service recommended.');
                    isValid = false;
                }

                // Comments
                const comments = document.getElementById('comments').value.trim();
                if (!comments) {
                    showError('err_comments', 'Comments are required.');
                    document.getElementById('comments').classList.add('is-invalid');
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

                // Notes
                const notes = document.getElementById('notes').value.trim();
                if (!notes) {
                    showError('err_notes', 'Notes are required.');
                    document.getElementById('notes').classList.add('is-invalid');
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

        document.getElementById('septicForm').addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return;
            }
            document.getElementById('loadingText').textContent = 'Submitting your form...';
            document.getElementById('loadingOverlay').style.display = 'flex';
        });

        showStep(currentStep);
    </script>

    <script>
        flatpickr("#date_of_pickup", {
            dateFormat: "m/d/Y",
            allowInput: true,
            disableMobile: true,
            maxDate: "today"
        });

        document.getElementById('calendar-icon-pickup').addEventListener('click', function() {
            document.getElementById('date_of_pickup')._flatpickr.open();
        });
    </script>

    <script>
        document.getElementById('saveBtn').addEventListener('click', function () {
            if (!validateStep(currentStep)) return;

            const btn = this;
            const overlay = document.getElementById('loadingOverlay');

            btn.disabled = true;
            btn.textContent = 'Saving...';
            document.getElementById('loadingText').textContent = 'Saving your draft...';
            overlay.style.display = 'flex';

            const formData = new FormData(document.getElementById('septicForm'));
            formData.append('is_draft', '1');

            fetch('{{ route("septic.draft") }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                overlay.style.display = 'none';
                btn.disabled = false;
                btn.textContent = 'Save Draft';
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Draft Saved!',
                        text: data.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: data.message || 'Could not save draft. Please try again.'
                    });
                }
            })
            .catch(function () {
                overlay.style.display = 'none';
                btn.disabled = false;
                btn.textContent = 'Save Draft';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.'
                });
            });
        });
    </script>

</body>

</html>
