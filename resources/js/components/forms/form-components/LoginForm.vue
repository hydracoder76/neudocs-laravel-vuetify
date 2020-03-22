<template>
	<div class="neu-row">
	<div id="neu-login-form" class="neu-login-form">
		<neu-form  dusk="neu-form" @neu-submit="submitLogin" :submission-uri="submitTo" v-if="!showMfa || !hasMfa">
		<div class="panel-heading text-center pb-3"> 
		 Login to neuDocs SRM</div>
			<div class="neu-login-container">
				<div>
					<p class="neu-input-error" :key="$index" v-for="(errorMsg, $index) in errors.email">{{ errorMsg }}</p>
				</div>
				<div>
					<b-form-group label="Email*" label-for="email">
						<input dusk="email" @keydown.enter.self="submitLogin" v-model.trim="email" type="text" id="email" class="neu-input neu-input-lg"/>
					</b-form-group>
				</div>
				<div class="neu-bump-d-sm"></div>
				<div>
					<b-form-group label="Password*" label-for="password">
						<input dusk="password" @keydown.enter.self="submitLogin" v-model.trim="password" type="password" id="password" class="neu-input neu-input-lg"/>
					</b-form-group>
				</div>
				<div class="neu-btn-cont">
					<div class="text-right w-100">
						<button dusk="neu-clear-btn" @click="resetFields" type="button" class="neu-cancel-button neu-btn-md">Clear</button>
						<button dusk="neu-login-btn" @click="submitLogin" type="button" class="neu-confirm-button neu-btn-md">Login</button>
					</div>
				</div>
			</div>

		</neu-form>
		
		<!-- it's technically part of logging in so that's why i'm including it here-->
		<transition name="slide-fade-mfa">
			<mfa-form dusk="neu-mfa-form"
					  v-if="hasMfa && showMfa && !showSetup"
					  :mfa-submission-uri="mfaUri"
					  :email="email"></mfa-form>

			<mfa-setup-form
					dusk="neu-mfa-setup-form"
					v-else-if="hasMfa && showMfa && showSetup" :email="email"
					:mfa-setup-uri="mfaSetupUri" :mfa-verify-uri="mfaVerifyUri"></mfa-setup-form>

		</transition>
		<neu-shadow-backdrop :show="isSubmitting"
							 :shadow-text="loginText"
							 :shadow-text-color="loginTextColor">
							 </neu-shadow-backdrop>
	</div>
		<div class="text-center pt-5 neu-forgot-password-div" v-show="!showMfa">
			<a dusk="neu-forgot-password-link" :href="forgotUrl"
			   class="neu-forgot-password-link neu-btn-md">I forgot my password.</a>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.slide-fade-mfa-enter-active {
		transition: all .3s ease;
	}
	.slide-fade-mfa-leave-active {
		transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}
	.slide-fade-mfa-enter, .slide-fade-mfa-leave-to {
		transform: translateX(10px);
		opacity: 0;
	}

	.neu-form{
		width: 100%;
		margin: auto;
	}
	.neu-forgot-password-div {
		width: 100%;
	}
  


</style> 
<script>

	import NeuForm from "../NeuForm";
	import MfaForm from "./MfaForm";
	import MfaSetupForm from "./MfaSetupForm";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
	import NeuShadowBackdrop from "../../util/NeuShadowBackdrop";
	import axios from "axios";


	export default {
		data() {
			return {
				email: "",
				password: "",
				isSubmitting: false,
				loginText: "Logging in...",
				loginTextColor: "#ffffff",
				hasError: false,
				errors: [],
				showMfa: false,
				mfaUri: "",
				hasMfa: false,
				showSetup: false,
				mfaSetupUri: "",
				mfaVerifyUri: "",
			};
		},
		computed: {
				getYear(){
					return new Date().getFullYear();
				}
		 },

		methods: {
			submitLogin() {
				// TODO: refactor, this is too complex
				this.isSubmitting = true;
				this.hasError = false;
				this.errorMsg = "";
				axios.post(this.submitTo, {
					"email": this.email,
					"password": this.password
				}).then(result => {
					this.hasMfa = result.data.data.has_mfa;
					if (result.data.data.mfa_setup) {
						this.showSetup = true;
						this.mfaSetupUri = result.data.data.mfa_setup_uri;
						this.mfaVerifyUri = result.data.data.mfa_verify_uri;
						this.showMfa = true;
						this.isSubmitting = false;
					}
					else {
						if (this.verifyAt !== "") {
							
							axios.post(this.verifyAt,
									{"email": this.email, "verify_token": result.data.data.verify_token}).then(res => {
								this.isSubmitting = false;
					
								// TODO: may not need the showMfa property, but we'll see
								if (this.hasMfa) {
									this.showMfa = true;

									this.mfaUri = res.data.data.mfa_uri;

								}
								else {
									this.showMfa = false;

									location.href = res.data.data.to;
								}

							}).catch(() => {
								this.isSubmitting = false
							});

						}
					}


				}).catch(error => {
					this.isSubmitting = false;
					this.hasError = true;
					this.errors = error.response.data.errors || {email: ["Generic login error, please contact an admin"]};
				});
			},
			resetFields() {
				this.email = "";
				this.password = "";
			}
		},
		props: {
			submitTo: {
				type: String,
				required: true
			},
			verifyAt: {
				type: String,
				required: false,
				default: ""
			},
			forgotUrl: {
				type: String,
				required: true
			}
		},
		components: {
			NeuForm,
			bFormGroup,
			NeuShadowBackdrop,
			MfaForm,
			MfaSetupForm,
			bFormCheckbox
		}

	}
</script>