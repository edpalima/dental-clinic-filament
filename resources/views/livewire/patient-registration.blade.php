<div>
    <div class="container mt-3">
        <form wire:submit.prevent="submitForm">
            <h4>Personal Information</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" wire:model="first_name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" wire:model="middle_name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" wire:model="last_name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="nickname">Nickname</label>
                        <input type="text" class="form-control" id="nickname" wire:model="nickname">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="religion">Religion</label>
                        <input type="text" class="form-control" id="religion" wire:model="religion" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="nationality">Nationality</label>
                        <input type="text" class="form-control" id="nationality" wire:model="nationality" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" wire:model="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="occupation">Occupation</label>
                        <input type="text" class="form-control" id="occupation" wire:model="occupation" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="contact_no">Contact No</label>
                        <input type="text" class="form-control" id="contact_no" wire:model="contact_no">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" wire:model="address">
                    </div>
                </div>

                <h4>Dental Insurance</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="dental_insurance">Insurance Name</label>
                            <input type="text" class="form-control" id="dental_insurance"
                                wire:model="dental_insurance">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="insurance_effective_date">Insurance Effective Date</label>
                            <input type="date" class="form-control" id="insurance_effective_date"
                                wire:model="insurance_effective_date">
                        </div>
                    </div>
                </div>

                <h4>Guardian Information For Minors</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="guardian_name">Guardian Name</label>
                            <input type="text" class="form-control" id="guardian_name" wire:model="guardian_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="guardian_occupation">Guardian Occupation</label>
                            <input type="text" class="form-control" id="guardian_occupation"
                                wire:model="guardian_occupation">
                        </div>
                    </div>
                </div>

                <h4>Referrer Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="referrer">Whom may we thank for referring you?</label>
                            <input type="text" class="form-control" id="referrer" wire:model="referrer">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="reason">What is your reason for dental consultation?</label>
                            <input type="text" class="form-control" id="reason" wire:model="reason">
                        </div>
                    </div>
                </div>

                <h4>Medical History</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>1. Are you in good health?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_good_health"
                                    id="good_health_yes" wire:model="is_good_health" value="true">
                                <label class="form-check-label" for="good_health_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_good_health"
                                    id="good_health_no" wire:model="is_good_health" value="false">
                                <label class="form-check-label" for="good_health_no">No</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>2. Are you under medical treatment now?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_medical_treatment"
                                    id="medical_treatment_yes" wire:model="is_medical_treatment" value="true">
                                <label class="form-check-label" for="medical_treatment_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_medical_treatment"
                                    id="medical_treatment_no" wire:model="is_medical_treatment" value="false">
                                <label class="form-check-label" for="medical_treatment_no">No</label>
                            </div>
                            <div class="form-group" wire:show="is_medical_treatment">
                                <label for="is_medical_treatment_name">If yes, what is the condition being
                                    treated?</label>
                                <input type="text" class="form-control" id="is_medical_treatment_name"
                                    wire:model="is_medical_treatment_name">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>3. Have you ever had a serious illness or surgical operation?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_illness_operated"
                                    id="illness_operated_yes" wire:model="is_illness_operated" value="true">
                                <label class="form-check-label" for="illness_operated_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_illness_operated"
                                    id="illness_operated_no" wire:model="is_illness_operated" value="false">
                                <label class="form-check-label" for="illness_operated_no">No</label>
                            </div>
                            <div class="form-group" wire:show="is_illness_operated">
                                <label for="is_illness_operated_name">If yes, when and why?</label>
                                <input type="text" class="form-control" id="is_illness_operated_name"
                                    wire:model="is_illness_operated_name">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>4. Have you ever been hospitalized?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_hospitalized"
                                    id="hospitalized_yes" wire:model="is_hospitalized" value="true">
                                <label class="form-check-label" for="hospitalized_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_hospitalized"
                                    id="hospitalized_no" wire:model="is_hospitalized" value="false">
                                <label class="form-check-label" for="hospitalized_no">No</label>
                            </div>
                            <div class="form-group" wire:show="is_hospitalized">
                                <label for="is_hospitalized_name">If yes, when and why?</label>
                                <input type="text" class="form-control" id="is_hospitalized_name"
                                    wire:model="is_hospitalized_name">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>5. Are you taking any prescription/non-prescription medication?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_has_medication"
                                    id="has_medication_yes" wire:model="is_has_medication" value="true">
                                <label class="form-check-label" for="has_medication_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_has_medication"
                                    id="has_medication_no" wire:model="is_has_medication" value="false">
                                <label class="form-check-label" for="has_medication_no">No</label>
                            </div>
                            <div class="form-group" wire:show="is_has_medication">
                                <label for="is_has_medication_name">If yes, please specify</label>
                                <input type="text" class="form-control" id="is_has_medication_name"
                                    wire:model="is_has_medication_name">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>6. Do you use tobacco products?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_using_tobacco"
                                    id="using_tobacco_yes" wire:model="is_using_tobacco" value="true">
                                <label class="form-check-label" for="using_tobacco_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_using_tobacco"
                                    id="using_tobacco_no" wire:model="is_using_tobacco" value="false">
                                <label class="form-check-label" for="using_tobacco_no">No</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>7. Do you use alcohol, cocaine, or other dangerous drugs?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_has_vice" id="has_vice_yes"
                                    wire:model="is_has_vice" value="true">
                                <label class="form-check-label" for="has_vice_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_has_vice" id="has_vice_no"
                                    wire:model="is_has_vice" value="false">
                                <label class="form-check-label" for="has_vice_no">No</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="allergic_id">8. Are you allergic to any of the following?</label>
                            <input type="number" class="form-control" id="allergic_id" wire:model="allergic_id"
                                placeholder="Enter ID for allergies">
                        </div>

                        <div class="form-group mb-3">
                            <label for="bleeding_time">9. Bleeding Time</label>
                            <input type="text" class="form-control" id="bleeding_time"
                                wire:model="bleeding_time">
                        </div>

                        <label>10. For women only:</label>
                        <div class="form-group mb-3">
                            <p>Are you pregnant?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_pregnant" id="pregnant_yes"
                                    wire:model="is_pregnant" value="true">
                                <label class="form-check-label" for="pregnant_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_pregnant" id="pregnant_no"
                                    wire:model="is_pregnant" value="false">
                                <label class="form-check-label" for="pregnant_no">No</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Are you nursing?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_nursing" id="nursing_yes"
                                    wire:model="is_nursing" value="true">
                                <label class="form-check-label" for="nursing_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_nursing" id="nursing_no"
                                    wire:model="is_nursing" value="false">
                                <label class="form-check-label" for="nursing_no">No</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Are you taking birth control pills?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_taking_pills"
                                    id="taking_pills_yes" wire:model="is_taking_pills" value="true">
                                <label class="form-check-label" for="taking_pills_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_taking_pills"
                                    id="taking_pills_no" wire:model="is_taking_pills" value="false">
                                <label class="form-check-label" for="taking_pills_no">No</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="blood_type">11. Blood Type</label>
                            <select class="form-control" id="blood_type" wire:model="blood_type" required>
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
                        </div>
                        <div class="form-group mb-3">
                            <label for="blood_pressure">12. Blood Pressure</label>
                            <input type="text" class="form-control" id="blood_pressure"
                                wire:model="blood_pressure">
                        </div>
                        <div class="form-group mb-3">
                            <label for="check_illness">13. Do you have or have you had any of the following? Check
                                which apply</label>
                            <div class="form-check" wire:model="check_illness">
                                {{-- Dynamically generated checkboxes from an array --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="high_blood_pressure"
                                        id="high_blood_pressure" wire:model="check_illness.high_blood_pressure">
                                    <label class="form-check-label" for="high_blood_pressure">High Blood
                                        Pressure</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="low_blood_pressure"
                                        id="low_blood_pressure" wire:model="check_illness.low_blood_pressure">
                                    <label class="form-check-label" for="low_blood_pressure">Low Blood
                                        Pressure</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="epilepsy_convulsions"
                                        id="epilepsy_convulsions" wire:model="check_illness.epilepsy_convulsions">
                                    <label class="form-check-label"
                                        for="epilepsy_convulsions">Epilepsy/Convulsions</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="aids_hiv" id="aids_hiv"
                                        wire:model="check_illness.aids_hiv">
                                    <label class="form-check-label" for="aids_hiv">AIDS or HIV Infection</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="std" id="std"
                                        wire:model="check_illness.std">
                                    <label class="form-check-label" for="std">Sexually Transmitted
                                        Disease</label>
                                </div>
                                {{-- Add more checkboxes for each condition following the pattern above --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="stomach_ulcers"
                                        id="stomach_ulcers" wire:model="check_illness.stomach_ulcers">
                                    <label class="form-check-label" for="stomach_ulcers">Stomach Troubles /
                                        Ulcers</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="fainting_seizure"
                                        id="fainting_seizure" wire:model="check_illness.fainting_seizure">
                                    <label class="form-check-label" for="fainting_seizure">Fainting Seizure</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="rapid_weight_loss"
                                        id="rapid_weight_loss" wire:model="check_illness.rapid_weight_loss">
                                    <label class="form-check-label" for="rapid_weight_loss">Rapid Weight Loss</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="radiation_therapy"
                                        id="radiation_therapy" wire:model="check_illness.radiation_therapy">
                                    <label class="form-check-label" for="radiation_therapy">Radiation Therapy</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="joint_replacement_implant"
                                        id="joint_replacement_implant"
                                        wire:model="check_illness.joint_replacement_implant">
                                    <label class="form-check-label" for="joint_replacement_implant">Joint Replacement
                                        / Implant</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="heart_surgery"
                                        id="heart_surgery" wire:model="check_illness.heart_surgery">
                                    <label class="form-check-label" for="heart_surgery">Heart Surgery</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="heart_attack"
                                        id="heart_attack" wire:model="check_illness.heart_attack">
                                    <label class="form-check-label" for="heart_attack">Heart Attack</label>
                                </div>
                                {{-- Continue listing all other conditions provided --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <input type="checkbox" class="form-check-input" id="check_consent"
                            wire:model="check_consent">
                        <label>
                            I accept the <a href="/consent-agreement" target="_blank" style="color: red">Consents
                                Agreement</a>
                        </label>
                    </div>
                </div>

                <div class="form-group text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </form>
    </div>
</div>
