<template>
    <div class="col-12">
        <b-alert
                :show="dismissCountDown"
                :variant="statusVar"
                dismissible fade
                @dismissed="dismissCountDown = 0"
                @dismiss-count-down="countDownChanged">
            <p v-for="(message, $index) in statusMessage" :key="$index">{{ message }}</p>
        </b-alert>
        <div class="neu-row">
            <div class="neu-left-container col-md-5">
                <transition name="hint" appear>
                    <ul class="hints">
                        <div class="hints-title">Password hints</div>
                        <li v-for="(error, $index) in passwordValidation.errors" :key="$index">{{ error }}</li>
                    </ul>
                </transition>
            </div>
            <div class="neu-right-container col-md-6">
                <label for="passwordInput" class="passwordLabel">Enter new password:</label>
                <input :class="{'form-control':true, 'is-valid':passwordValid, 'is-invalid':passwordInvalid}"
                       @keyup="checkOnValidationErrors"
                       id="passwordInput"
                       type="password"
                       v-model="password">

                <label for="passwordConfirm" class="passwordLabel">Enter new password again:</label>
                <input :class="{'form-control':true, 'is-valid':confirmPasswordValid, 'is-invalid':confirmPasswordInvalid}"
                       id="passwordConfirm"
                       type="password"
                       v-model="confirmPassword">

                <div class="matches" v-if='notSamePasswords'>
                    <p class="text-danger">• Passwords do not match.</p>
                </div>
                <div class="neu-btn-cont">
                    <div class="neu-btn-cont-right">
                        <neu-button btn-text="Save New Password" @neu-btn-click="savePassword"></neu-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .passwordLabel {
        margin-left:20px;
        margin-right:15px;
    }
    .hints {
        max-width: 500px;
        padding: 0.5em;
        margin: 1em 0;
        font-size: 15px;
        color: #161c23;
    }
    .hints p {
        margin: 0;
        padding-left: 1em;
    }
    .hints p ::before {
        content:">";
        font-size: 20px;
        margin-right:6px;
        display:inline-block;
    }
    .matches {
        max-width: 500px;
        padding: 0.5em;
        font-size: 20px;
        color: #c71e22;
    }
    .matches p {
        padding-left: 1em;
    }
</style>

<script>
	import axios from "axios"
	import neuButton from "./util/NeuButton";
	import bAlert from "bootstrap-vue/es/components/alert/alert";

	export default {

		mounted(){

		},
		data() {
			return {
                password: "",
                confirmPassword: "",
                dismissCountDown: 0,
                statusVar: "success",
                statusMessage: "",
                dismissSecs: 5,
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
		methods: {
            savePassword() {
                axios.post(this.resetPassUrl, {
                    password: this.password,
                    password_confirmation: this.confirmPassword
                }).then(result => {
                    if (result.data.message) {
                        this.successMessage.push(result.data.message);
                        this.showAlert(this.successMessage, "success")
                    }
                    setTimeout(function() {
                        location.href = "/";
                    }, 1000);
                }).catch(error => {
                    if (error.response.status === 404) {
                        this.showAlert('Error: ' + error.response.data.message, "danger")
                    } else {
                        this.formatMessageForErrorAlert(error.response.data.errors);
                    }
				});
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            showAlert(message,variant) {
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
                if (this.passwordValidation.valid === false && this.password) {
                    this.passwordValid = false;
                    this.passwordInvalid = true;
                } else if (this.passwordValidation.valid === true && this.password) {
                    this.passwordValid = true;
                    this.passwordInvalid = false;
                } else if (!this.password) {
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
        computed: {
            notSamePasswords() {
                if (this.password && this.confirmPassword) {
                    if (this.password !== this.confirmPassword) {
                        this.confirmPasswordValid = false;
                        this.confirmPasswordInvalid = true;
                        return true;
                    }

                    this.confirmPasswordValid = true;
                    this.confirmPasswordInvalid = false;
                    return false;
                }
            },
            passwordValidation() {
                let errors = [];
                if (!this.password) {
                    for (let condition of this.rules) {
                        errors.push(condition.message)
                    }
                } else {
                    for (let condition of this.rules) {
                        if (!condition.regex.test(this.password)) {
                            errors.push(condition.message)
                        }
                    }
                }

                if (errors.length === 0) {
                    return { valid: true, errors }
                }
                return { valid: false, errors }
            },
        },
		props: {
            resetPassUrl: {
            	type: String,
                require: true,
            }
		},
		components: {
            neuButton, bAlert
		},
	}
</script>
