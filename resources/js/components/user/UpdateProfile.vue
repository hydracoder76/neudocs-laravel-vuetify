<template>
  <div class="col-12">
      <b-alert dusk="statusCRUD"
               :show="dismissCountDown"
               :variant="statusVar"
               dismissible fade
               @dismissed="dismissCountDown = 0"
               @dismiss-count-down="countDownChanged">
          <p v-for="(message, $index) in statusMessage" :key="$index">{{ message }}</p>
      </b-alert>
    <div class="neu-row">
      <div class="neu-left-container col-md-6">
        <transition name="hint" appear>
          <ul class="hints">
               <div class="hints-title">Password hints</div>
            <li v-for="(error, $index) in passwordValidation.errors" :key="$index">{{ error }}</li>
          </ul>
        </transition>
      </div>
        <div class="neu-right-container col-md-6">
            <neu-form dusk="formAdd" @submit.prevent="updateUserProfile" :submission-uri="submitTo">
            <b-form-group v-for="(item) in editableColumns" label-cols="4" label-cols-lg="2" label-size="Small" horizontal :label="item.label" :key="item.id">
                <template v-if="item.type === 'TEXT'">
                    <input :dusk="item.key" v-model.trim="dataAdd[item.key]" :type="item.type" class="form-control neu-input"/>
                </template>

                <template v-if="item.key === 'password'">
                    <input :class="{'form-control':true, 'is-valid':passwordValid, 'is-invalid':passwordInvalid, 'neu-input':true}"
                            @keyup="checkOnValidationErrors"
                            :dusk="item.key"
                            v-model.trim="dataAdd[item.key]"
                            :type="item.type"/>
                </template>

                <template v-if="item.key === 'confirmPassword'">
                    <input :class="{'form-control':true, 'is-valid':confirmPasswordValid, 'is-invalid':confirmPasswordInvalid, 'neu-input':true}"
                            :dusk="item.key"
                            v-model.trim="dataAdd[item.key]"
                            :type="item.type"/>
                    <div class="matches" v-if='notSamePasswords'>
                        <span>Passwords do not match.</span>
                    </div>
                </template>
            </b-form-group>
            <div class="float-right">
                <neu-button btn-size="md" btn-type="confirm" btn-text="Update" dusk="neu-user-profile-save-btn"
                            @neu-btn-click="updateUserProfile"></neu-button>
            </div>
            </neu-form>
        </div>
     
    </div>


  </div>
</template>
<style scoped>
</style>

<script>
    import bAlert from "bootstrap-vue/es/components/alert/alert";
    import bForm from "bootstrap-vue/es/components/form/form";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import NeuForm from "../forms/NeuForm"
    import NeuButton from "../util/NeuButton";
    import axios from "axios"

    export default {
        name: "UpdateProfile",
        components: {
            bAlert,
            bForm,
            bFormGroup,
            NeuForm,
            NeuButton,
        },
        props: {
            locationUri: {
                type: String,
                required: true
            },
            submitTo: {
                type: String,
                required: true
            },
            user: {
                type: String,
                required: true
            }
        },
        mounted() {
            this.initialLoad();
            this.editableColumns = this.handleColumns(this.dataColumns)
        },
        methods: {
            initialLoad() {
                const loggedUser = JSON.parse(this.user);
                this.dataAdd.name = loggedUser.name;
                this.dataAdd.email = loggedUser.email;
            },
            handleColumns(editableColumn) {
                const tempEditableColumn = editableColumn.filter((item) => {
                    return item.editable && item;
                })
                return tempEditableColumn;
            },
            updateUserProfile() {
                axios.put(this.locationUri, {
                    name: this.dataAdd.name,
                    email: this.dataAdd.email,
                    password: this.dataAdd.password,
                    password_confirmation: this.dataAdd.confirmPassword
                }).then(result => {
                    if (result.data.message) {
                        this.successMessage.push(result.data.message);
                        this.showAlert(this.successMessage, "success")
                    }
                    if (result.data.data.redirect_to) {
                        window.location = result.data.data.redirect_to;
                    }
                }).catch(error => {
                    if (error.response.status >= 400 && error.response.status <= 500) {
                        this.showAlert([error.response.data.message], "danger");
                    }
                    else {
                        this.formatMessageForErrorAlert(error.response.data.errors);
                    }
                });
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            showAlert(message, variant){
                this.dismissCountDown = this.dismissSecs;
                this.statusMessage = message;
                this.statusVar = variant;
            },
            formatMessageForErrorAlert(errorObj) {
                let errorList = [];
                const errors = Object.keys(errorObj).forEach(errorKey => {
                    const specificErrorList = [];
                    errorObj[errorKey].forEach(item => {
                        specificErrorList.push(item);
                    });
                    errorList = errorList.concat(specificErrorList)
                });
                this.showAlert(errorList, "danger");
            },
            checkOnValidationErrors() {
                if (this.passwordValidation.valid === false && this.dataAdd.password) {
                    this.passwordValid = false;
                    this.passwordInvalid = true;
                } else if (this.passwordValidation.valid === true && this.dataAdd.password) {
                    this.passwordValid = true;
                    this.passwordInvalid = false;
                } else if (!this.dataAdd.password) {
                    this.passwordValid = false;
                    this.passwordInvalid = false;
                    this.hideValidationConfirmPassword();
                }
            },
            hideValidationConfirmPassword() {
                this.confirmPasswordValid = false;
                this.confirmPasswordInvalid = false;
            }
        },
        data () {
            return {
                dataAdd: [],
                editableColumns: [],
                dismissSecs: 5,
                dismissCountDown: 0,
                showDismissibleAlert: false,
                statusMessage: "",
                statusVar: "",
                dataTable: [],
                dataColumns: [
                    {key: "name", label: "Name", visible: true, sortable: true, sortDirection: "desc", editable: true, "type":"TEXT" },
                    {key: "email", label: "Email", visible: true, sortable: true, editable: true, "type":"TEXT" },
                    {key: "password", label: "Password", visible: false, sortable: false, editable: true, "type":"PASSWORD" },
                    {key: "confirmPassword", label: "Confirm password", visible: false, sortable: false, editable: true, "type":"PASSWORD" },
                ],
                password: "",
                rules: [
                    { message: "Password minimum length is 8 alphanumeric characters.", regex:/.{8,}/ },
                    { message: "One lowercase letter required.", regex:/[a-z]+/ },
                    { message: "One uppercase letter required.", regex:/[A-Z]+/ },
                    { message: "Password must have at least 2 numeric characters.", regex:/.*[0-9].*[0-9].*/ },
                    { message: "Numeric characters must not be at the beginning or the ending of password.", regex:/^[^0-9].*[^0-9]$/ },
                    { message: "Password must include at least one special character from the following list: !@#$%^&*()_+-={}|[]~`:\";'<>?,./", regex:/[!@#$%^&*()_\+\-\=\{\}|\[\]~`:";'<>?,.\/]+/ },
                    { message: "Password must not contain spaces.", regex:/^\S*$/ },
                ],
                submitted: false,
                passwordValid: false,
                passwordInvalid: false,
                confirmPasswordValid: false,
                confirmPasswordInvalid: false,
                successMessage: []
            }
        },
        computed: {
            notSamePasswords () {
                if (this.dataAdd.password && this.dataAdd.confirmPassword) {
                    if (this.dataAdd.password !== this.dataAdd.confirmPassword) {
                        this.confirmPasswordValid = false;
                        this.confirmPasswordInvalid = true;
                        return true;
                    } else {
                        this.confirmPasswordValid = true;
                        this.confirmPasswordInvalid = false;
                        return false;
                    }
                }
            },
            passwordValidation () {
                let errors = [];
                if (!this.dataAdd.password) {
                    for (let condition of this.rules) {
                        errors.push(condition.message)
                    }
                } else {
                    for (let condition of this.rules) {
                        if (!condition.regex.test(this.dataAdd.password)) {
                            errors.push(condition.message)
                        }
                    }
                }
                if (errors.length === 0) {
                    return { valid: true, errors }
                }
                return { valid: false, errors }
            },
        }
    }

</script>
