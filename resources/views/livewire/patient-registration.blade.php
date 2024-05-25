<div>
    <div class="container mt-2">
        <div class="text-center mb-1">
            <p>Sign up to schedule your appointment or <a class="text-success" href="/admin">log in</a> to manage your existing bookings</p>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ URL::to('assets/img/logo.png') }}" alt="Logo">
                </div>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="submitForm">
                    <h4>Personal Information</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" wire:model="first_name">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                    id="middle_name" wire:model="middle_name">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" wire:model="last_name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="nickname">Nickname</label>
                                <input type="text" class="form-control @error('nickname') is-invalid @enderror"
                                    id="nickname" wire:model="nickname">
                                @error('nickname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror"
                                    id="religion" wire:model="religion">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                    id="nationality" wire:model="nationality">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="gender">Gender</label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                    wire:model="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror"
                                    id="occupation" wire:model="occupation">
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="contact_no">Contact Number</label>
                                <input type="number" class="form-control @error('contact_no') is-invalid @enderror"
                                    id="contact_no" wire:model="contact_no">
                                @error('contact_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" wire:model="address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4>Dental Insurance Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="dental_insurance">Dental Insurance</label>
                                <input type="text"
                                    class="form-control @error('dental_insurance') is-invalid @enderror"
                                    id="dental_insurance" wire:model="dental_insurance">
                                @error('dental_insurance')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="insurance_effective_date">Insurance Effective Date</label>
                                <input type="date"
                                    class="form-control @error('insurance_effective_date') is-invalid @enderror"
                                    id="insurance_effective_date" wire:model="insurance_effective_date"
                                    placeholder="YYYY-MM-DD">
                                @error('insurance_effective_date')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4>Guardian Information For Minors</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="guardian_name">Guardian Name</label>
                                <input type="text"
                                    class="form-control @error('guardian_name') is-invalid @enderror"
                                    id="guardian_name" wire:model="guardian_name">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="guardian_occupation">Guardian Occupation</label>
                                <input type="text"
                                    class="form-control @error('guardian_occupation') is-invalid @enderror"
                                    id="guardian_occupation" wire:model="guardian_occupation">
                                @error('guardian_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4>Referrer Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="referrer">Whom may we thank for referring you?</label>
                                <input type="text" class="form-control @error('referrer') is-invalid @enderror"
                                    id="referrer" wire:model="referrer">
                                @error('referrer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="reason">What is your reason for dental consultation?</label>
                                <input type="text" class="form-control @error('reason') is-invalid @enderror"
                                    id="reason" wire:model="reason">
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4>Dental History</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="previous_dentist" class="form-label">Previous Dentist</label>
                                <input type="text"
                                    class="form-control @error('previous_dentist') is-invalid @enderror"
                                    id="previous_dentist" wire:model="previous_dentist" maxlength="255"
                                    placeholder="Enter previous dentist's name">
                                @error('previous_dentist')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="last_dental_visit" class="form-label">Last Dental Visit</label>
                                <input type="date"
                                    class="form-control @error('last_dental_visit') is-invalid @enderror"
                                    id="last_dental_visit" wire:model="last_dental_visit">
                                @error('last_dental_visit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <h4>Medical History</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="physician_name" class="form-label">Physician Name</label>
                                <input type="text"
                                    class="form-control @error('physician_name') is-invalid @enderror"
                                    id="physician_name" wire:model="physician_name" maxlength="255"
                                    wire:model="physician_name">
                                @error('physician_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="physician_specialty" class="form-label">Physician Specialty</label>
                                <input type="text"
                                    class="form-control @error('physician_specialty') is-invalid @enderror"
                                    id="physician_specialty" wire:model="physician_specialty" maxlength="255"
                                    wire:model="physician_specialty">
                                @error('physician_specialty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="physician_office" class="form-label">Office Address</label>
                                <input type="text"
                                    class="form-control @error('physician_office') is-invalid @enderror"
                                    id="physician_office" wire:model="physician_office" maxlength="255"
                                    wire:model="physician_office">
                                @error('physician_office')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="physician_number" class="form-label">Office Number</label>
                                <input type="number"
                                    class="form-control @error('physician_number') is-invalid @enderror"
                                    id="physician_number" wire:model="physician_number" maxlength="255"
                                    wire:model="physician_number">
                                @error('physician_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>1. Are you in good health?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_good_health') is-invalid @enderror"
                                        type="radio" name="is_good_health" id="good_health_yes"
                                        wire:model="is_good_health" value="1">
                                    <label class="form-check-label" for="good_health_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_good_health') is-invalid @enderror"
                                        type="radio" name="is_good_health" id="good_health_no"
                                        wire:model="is_good_health" value="0">
                                    <label class="form-check-label" for="good_health_no">No</label>
                                </div>
                                @error('is_good_health')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>2. Are you under medical treatment now?</label>
                                <div class="form-check">
                                    <input
                                        class="form-check-input @error('is_medical_treatment') is-invalid @enderror"
                                        type="radio" name="is_medical_treatment" id="medical_treatment_yes"
                                        wire:model="is_medical_treatment" value="1">
                                    <label class="form-check-label" for="medical_treatment_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input @error('is_medical_treatment') is-invalid @enderror"
                                        type="radio" name="is_medical_treatment" id="medical_treatment_no"
                                        wire:model="is_medical_treatment" value="0">
                                    <label class="form-check-label" for="medical_treatment_no">No</label>
                                </div>
                                @error('is_medical_treatment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <label for="is_medical_treatment_name">If yes, what is the condition being
                                        treated?</label>
                                    <input type="text"
                                        class="form-control @error('is_medical_treatment_name') is-invalid @enderror"
                                        id="is_medical_treatment_name" wire:model="is_medical_treatment_name">
                                    @error('is_medical_treatment_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>3. Have you ever had a serious illness or surgical operation?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_illness_operated') is-invalid @enderror"
                                        type="radio" name="is_illness_operated" id="illness_operated_yes"
                                        wire:model="is_illness_operated" value="1">
                                    <label class="form-check-label" for="illness_operated_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_illness_operated') is-invalid @enderror"
                                        type="radio" name="is_illness_operated" id="illness_operated_no"
                                        wire:model="is_illness_operated" value="0">
                                    <label class="form-check-label" for="illness_operated_no">No</label>
                                </div>
                                @error('is_illness_operated')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <label for="is_illness_operated_name">If yes, when and why?</label>
                                    <input type="text"
                                        class="form-control @error('is_illness_operated_name') is-invalid @enderror"
                                        id="is_illness_operated_name" wire:model="is_illness_operated_name">
                                    @error('is_illness_operated_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>4. Have you ever been hospitalized?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_hospitalized') is-invalid @enderror"
                                        type="radio" name="is_hospitalized" id="hospitalized_yes"
                                        wire:model="is_hospitalized" value="1">
                                    <label class="form-check-label" for="hospitalized_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_hospitalized') is-invalid @enderror"
                                        type="radio" name="is_hospitalized" id="hospitalized_no"
                                        wire:model="is_hospitalized" value="0">
                                    <label class="form-check-label" for="hospitalized_no">No</label>
                                </div>
                                @error('is_hospitalized')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <label for="is_hospitalized_name">If yes, when and why?</label>
                                    <input type="text"
                                        class="form-control @error('is_hospitalized_name') is-invalid @enderror"
                                        id="is_hospitalized_name" wire:model="is_hospitalized_name">
                                    @error('is_hospitalized_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>5. Are you taking any prescription/non-prescription medication?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_has_medication') is-invalid @enderror"
                                        type="radio" name="is_has_medication" id="has_medication_yes"
                                        wire:model="is_has_medication" value="1">
                                    <label class="form-check-label" for="has_medication_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_has_medication') is-invalid @enderror"
                                        type="radio" name="is_has_medication" id="has_medication_no"
                                        wire:model="is_has_medication" value="0">
                                    <label class="form-check-label" for="has_medication_no">No</label>
                                </div>
                                @error('is_has_medication')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <label for="is_has_medication_name">If yes, please specify</label>
                                    <input type="text"
                                        class="form-control @error('is_has_medication_name') is-invalid @enderror"
                                        id="is_has_medication_name" wire:model="is_has_medication_name">
                                    @error('is_has_medication_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>6. Do you use tobacco products?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_using_tobacco') is-invalid @enderror"
                                        type="radio" name="is_using_tobacco" id="using_tobacco_yes"
                                        wire:model="is_using_tobacco" value="1">
                                    <label class="form-check-label" for="using_tobacco_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_using_tobacco') is-invalid @enderror"
                                        type="radio" name="is_using_tobacco" id="using_tobacco_no"
                                        wire:model="is_using_tobacco" value="0">
                                    <label class="form-check-label" for="using_tobacco_no">No</label>
                                </div>
                                @error('is_using_tobacco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>7. Do you use alcohol, cocaine, or other dangerous drugs?</label>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_has_vice') is-invalid @enderror"
                                        type="radio" name="is_has_vice" id="has_vice_yes" wire:model="is_has_vice"
                                        value="1">
                                    <label class="form-check-label" for="has_vice_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('is_has_vice') is-invalid @enderror"
                                        type="radio" name="is_has_vice" id="has_vice_no" wire:model="is_has_vice"
                                        value="0">
                                    <label class="form-check-label" for="has_vice_no">No</label>
                                </div>
                                @error('is_has_vice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>8. Are you allergic to any of the following?</label>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="check_allergies" value="local_anesthetic"
                                        id="local_anesthetic" class="form-check-input">
                                    <label class="form-check-label" for="local_anesthetic">Local Anesthetic</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="check_allergies" value="sulfa_drugs"
                                        id="sulfa_drugs" class="form-check-input">
                                    <label class="form-check-label" for="sulfa_drugs">Sulfa drugs</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="check_allergies" value="aspirin"
                                        id="aspirin" class="form-check-input">
                                    <label class="form-check-label" for="aspirin">Aspirin</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="check_allergies" value="latext"
                                        id="latext" class="form-check-input">
                                    <label class="form-check-label" for="latext">Latext</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="check_allergies" value="others"
                                        id="others" class="form-check-input">
                                    <label class="form-check-label" for="others">Others</label>
                                </div>
                                @error('check_allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="bleeding_time">9. Bleeding Time</label>
                                <input type="text"
                                    class="form-control @error('bleeding_time') is-invalid @enderror"
                                    id="bleeding_time" wire:model="bleeding_time">
                                @error('bleeding_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label>10. For women only:</label>
                            <div style="margin-left: 15px">
                                <div class="form-group mb-3">
                                    <p>Are you pregnant?</p>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_pregnant') is-invalid @enderror"
                                            type="radio" name="is_pregnant" id="pregnant_yes"
                                            wire:model="is_pregnant" value="1">
                                        <label class="form-check-label" for="pregnant_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_pregnant') is-invalid @enderror"
                                            type="radio" name="is_pregnant" id="pregnant_no"
                                            wire:model="is_pregnant" value="0">
                                        <label class="form-check-label" for="pregnant_no">No</label>
                                    </div>
                                    @error('is_pregnant')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Are you nursing?</label>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_nursing') is-invalid @enderror"
                                            type="radio" name="is_nursing" id="nursing_yes"
                                            wire:model="is_nursing" value="1">
                                        <label class="form-check-label" for="nursing_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_nursing') is-invalid @enderror"
                                            type="radio" name="is_nursing" id="nursing_no" wire:model="is_nursing"
                                            value="0">
                                        <label class="form-check-label" for="nursing_no">No</label>
                                    </div>
                                    @error('is_nursing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Are you taking birth control pills?</label>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_taking_pills') is-invalid @enderror"
                                            type="radio" name="is_taking_pills" id="taking_pills_yes"
                                            wire:model="is_taking_pills" value="1">
                                        <label class="form-check-label" for="taking_pills_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_taking_pills') is-invalid @enderror"
                                            type="radio" name="is_taking_pills" id="taking_pills_no"
                                            wire:model="is_taking_pills" value="0">
                                        <label class="form-check-label" for="taking_pills_no">No</label>
                                    </div>
                                    @error('is_taking_pills')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="blood_type">11. Blood Type</label>
                                <select class="form-control @error('blood_type') is-invalid @enderror"
                                    id="blood_type" wire:model="blood_type" required>
                                    <option value="">Choose...</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                                @error('blood_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="blood_pressure">12. Blood Pressure</label>
                                <input type="text"
                                    class="form-control @error('blood_pressure') is-invalid @enderror"
                                    id="blood_pressure" wire:model="blood_pressure">
                                @error('blood_pressure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-2" for="check_illness">13. Do you have or have you had any of the following? Check
                                    which apply</label>
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="high_blood_pressure" wire:model="check_illness"
                                                value="high_blood_pressure">
                                            <label class="form-check-label" for="high_blood_pressure">High Blood
                                                Pressure</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="low_blood_pressure" wire:model="check_illness"
                                                value="low_blood_pressure">
                                            <label class="form-check-label" for="low_blood_pressure">Low Blood
                                                Pressure</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="epilepsy_convulsions" wire:model="check_illness"
                                                value="epilepsy_convulsions">
                                            <label class="form-check-label"
                                                for="epilepsy_convulsions">Epilepsy/Convulsions</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="aids_hiv" wire:model="check_illness"
                                                value="aids_hiv">
                                            <label class="form-check-label" for="aids_hiv">AIDS or HIV
                                                Infection</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="std" wire:model="check_illness"
                                                value="std">
                                            <label class="form-check-label" for="std">Sexually Transmitted
                                                Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="stomach_ulcers" wire:model="check_illness"
                                                value="stomach_ulcers">
                                            <label class="form-check-label" for="stomach_ulcers">Stomach Troubles /
                                                Ulcers</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="fainting_seizure" wire:model="check_illness"
                                                value="fainting_seizure">
                                            <label class="form-check-label" for="fainting_seizure">Fainting
                                                Seizure</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="rapid_weight_loss" wire:model="check_illness"
                                                value="rapid_weight_loss">
                                            <label class="form-check-label" for="rapid_weight_loss">Rapid Weight
                                                Loss</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="radiation_therapy" wire:model="check_illness"
                                                value="radiation_therapy">
                                            <label class="form-check-label" for="radiation_therapy">Radiation
                                                Therapy</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="joint_replacement_implant"
                                                wire:model="check_illness" value="joint_replacement_implant">
                                            <label class="form-check-label" for="joint_replacement_implant">Joint
                                                Replacement
                                                / Implant</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="heart_surgery" wire:model="check_illness"
                                                value="heart_surgery">
                                            <label class="form-check-label" for="heart_surgery">Heart Surgery</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="heart_attack" wire:model="check_illness"
                                                value="heart_attack">
                                            <label class="form-check-label" for="heart_attack">Heart Attack</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="thyroid_problem" wire:model="check_illness"
                                                value="thyroid_problem">
                                            <label class="form-check-label" for="thyroid_problem">Thyroid
                                                Problem</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="heart_disease" wire:model="check_illness"
                                                value="heart_disease">
                                            <label class="form-check-label" for="heart_disease">Heart Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="heart_murmur" wire:model="check_illness"
                                                value="heart_murmur">
                                            <label class="form-check-label" for="heart_murmur">Heart Murmur</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="hepatitis_liver_disease"
                                                wire:model="check_illness" value="hepatitis_liver_disease">
                                            <label class="form-check-label"
                                                for="hepatitis_liver_disease">Hepatitis/Liver
                                                Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="rheumatic_fever" wire:model="check_illness"
                                                value="rheumatic_fever">
                                            <label class="form-check-label" for="rheumatic_fever">Rheumatic
                                                Fever</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="hay_fever_allergies" wire:model="check_illness"
                                                value="hay_fever_allergies">
                                            <label class="form-check-label" for="hay_fever_allergies">Hay Fever /
                                                Allergies</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="respiratory_problems" wire:model="check_illness"
                                                value="respiratory_problems">
                                            <label class="form-check-label" for="respiratory_problems">Respiratory
                                                Problems</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="hepatitis_jaundice" wire:model="check_illness"
                                                value="hepatitis_jaundice">
                                            <label class="form-check-label" for="hepatitis_jaundice">Hepatitis /
                                                Jaundice</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="tuberculosis" wire:model="check_illness"
                                                value="tuberculosis">
                                            <label class="form-check-label" for="tuberculosis">Tuberculosis</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="swollen_ankles" wire:model="check_illness"
                                                value="swollen_ankles">
                                            <label class="form-check-label" for="swollen_ankles">Swollen
                                                Ankles</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="kidney_disease" wire:model="check_illness"
                                                value="kidney_disease">
                                            <label class="form-check-label" for="kidney_disease">Kidney
                                                Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="diabetes" wire:model="check_illness"
                                                value="diabetes">
                                            <label class="form-check-label" for="diabetes">Diabetes</label>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="chest_pain" wire:model="check_illness"
                                                value="chest_pain">
                                            <label class="form-check-label" for="chest_pain">Chest Pain</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="stroke" wire:model="check_illness"
                                                value="stroke">
                                            <label class="form-check-label" for="stroke">Stroke</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="cancer_tumors" wire:model="check_illness"
                                                value="cancer_tumors">
                                            <label class="form-check-label" for="cancer_tumors">Cancer/Tumors</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="anemia" wire:model="check_illness"
                                                value="anemia">
                                            <label class="form-check-label" for="anemia">Anemia</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="angina" wire:model="check_illness"
                                                value="angina">
                                            <label class="form-check-label" for="angina">Angina</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="asthma" wire:model="check_illness"
                                                value="asthma">
                                            <label class="form-check-label" for="asthma">Asthma</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="emphysema" wire:model="check_illness"
                                                value="emphysema">
                                            <label class="form-check-label" for="emphysema">Emphysema</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="bleeding_problems" wire:model="check_illness"
                                                value="bleeding_problems">
                                            <label class="form-check-label" for="bleeding_problems">Bleeding
                                                Problems</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="blood_diseases" wire:model="check_illness"
                                                value="blood_diseases">
                                            <label class="form-check-label" for="blood_diseases">Blood
                                                Diseases</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="head_injuries" wire:model="check_illness"
                                                value="head_injuries">
                                            <label class="form-check-label" for="head_injuries">Head Injuries</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="arthritis_rheumatism" wire:model="check_illness"
                                                value="arthritis_rheumatism">
                                            <label class="form-check-label" for="arthritis_rheumatism">Arthritis /
                                                Rheumatism</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input @error('check_illness') is-invalid @enderror"
                                                type="checkbox" id="other" wire:model="check_illness"
                                                value="other">
                                            <label class="form-check-label" for="other">Other</label>
                                        </div>
                                    </div>

                                    @error('check_illness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Credentials</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" wire:model="email" required>
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" wire:model="password" required>
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="passwordConfirmation">Confirm Password</label>
                                <input type="password"
                                    class="form-control @error('passwordConfirmation') is-invalid @enderror"
                                    id="passwordConfirmation" wire:model="passwordConfirmation" required>
                                @error('passwordConfirmation')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="check_consent"
                                    wire:model="check_consent">
                                <label class="form-check-label" for="check_consent">
                                    I accept the <a href="/consent-agreement" target="_blank"
                                        class="text-danger">Consent
                                        Agreement</a>
                                </label>
                            </div>
                            @error('check_consent')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
