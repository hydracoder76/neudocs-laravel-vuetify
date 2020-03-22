<template>
    <div class="col-md-10 offset-md-2 neu-row">
        <div class="col-md-12">
            <h1 class="panel-heading">Multi Factor Authentication</h1>
        </div>

        <div class="col-md-6">
            <p>Please enter the code from Google Authenticator to verify your login. Steps are as follows:</p>
            <ol class="neu-smaller-text">
                <li>Pull up the Google Authenticator app on your mobile device.</li>
                <li>Look at the number code shown in the app. If you have multiple accounts set up in
                    Google Authenticator, look at the code accompanying the email address you're logging in with,
                    and the words "Neubus SRM".
                </li>
                <li>Type the six digits in at right, and click Submit.</li>
            </ol>
            <p class="neu-smaller-text"><em>Be careful, each code only lasts 30 seconds! If the code no longer appears
                on your phone or
                tablet, it will no longer be valid to enter here.</em></p>

        </div>
        <div class="col-md-3 p-0">
            <neu-form dusk="neu-mfa-form"
                      class="neu-mfa-form"
                      @neu-submit="submitMfa"
                      :regular-form-submit="false"
                      :submission-uri="mfaSubmissionUri">
                <div>
                    <p class="neu-input-error" v-for="(error, index) in errorMessages" :key="index">{{ error[index] }}</p>
                    <b-form-group label="Enter MFA token here" label-for="neu-mfa-token">
                        <input :id="fieldIdPrefix + index"
                               autocomplete="false" type="text"
                               :tabindex="index + 1"
                               v-for="(field, index) in numMfaFields"
                               v-model="tokenPieces[index]"
                               @keydown.enter.self="submitMfa" maxlength="1"
                               @keyup.self="tokenPieceEntered(index, $event)"
                               dusk="neu-mfa-token" class="neu-input neu-input-single"/>
                    </b-form-group>
                </div>

                <div class="neu-btn-cont">

                    <button type="button" id="neu-submit-mfa" @click="submitMfa" dusk="neu-submit-mfa"
                            class="neu-confirm-button neu-btn-md">Submit
                    </button>

                </div>
            </neu-form>
        </div>
        <neu-shadow-backdrop :show="isSubmittingMfaVerification"
                             :shadow-text="mfaVerificationText"></neu-shadow-backdrop>
    </div>
</template>

<style scoped lang="scss">
    .neu-smaller-text {
        font-size: 0.9rem;
    }
</style>

<script>

    import NeuForm from "../NeuForm";
    import neuInputLinked from "../../mixins/neu-input-linked";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import axios from "axios";
    import NeuShadowBackdrop from "../../util/NeuShadowBackdrop";

    export default {
        data() {
            return {
                tokenPieces: [],
                numMfaFields: 6,
                fieldIdPrefix: "neu-mfa-token-",
                errorMessages: [],
                isSubmittingMfaVerification: false,
                mfaVerificationText: "Verifying, please hold",
                isOverAttempts: false // this is mainly for front end use, the backend will do its own validation
            };
        },
        components: {
            NeuForm, bFormGroup, NeuShadowBackdrop
        },
        computed: {
            showRetries() {
                // this is only for display. the numbers will still be validated
                // server side to prevent anything naughty on the front end
                return this.maxRetries < 0;
            }
        },
        mixins: [neuInputLinked],
        methods: {

            submitMfa() {
                let submissionParams = {
                    email: this.email,
                    token: this.tokenPieces.join("")
                };
                if (this.verifyTokenUri !== null) {
                    this.isSubmittingMfaVerification = true;
                    axios.post(this.verifyTokenUri, {
                        email: this.email
                    }).then(response => {
                        submissionParams.verification_token = response.data.data.verify_token;
                        this.doSubmission(submissionParams);
                    }).catch(error => {
                        this.errorMessages = error.response.errors;
                        this.isSubmittingMfaVerification = false;
                        this.runningTokenStringSize = 0;
                        this.resetCursorPosition();
                        this.$set(this.$data, "tokenPieces", []);
                    });
                } else {
                    this.runningTokenStringSize = 0;
                    this.resetCursorPosition();
                    this.$set(this.$data, "tokenPieces", []);
                    return this.doSubmission(submissionParams);
                }
                // just in case we make it out here somehow
                this.isSubmittingMfaVerification = false;

            },

            // we don't necessarily care about form verification at the point of login since they've already been
            // verified, and trying to hijack the form won't do anything since the verification will be destroyed
            // and a relog has to happen.
            // in the case here, a user must be authed in order for anything to actually go through in the first place
            doSubmission(params) {
                axios.post(this.mfaSubmissionUri,
                    params
                ).then(result => {
                    this.runningTokenStringSize = 0;
                    this.$set(this.$data, "tokenPieces", []);
                    this.isSubmittingMfaVerification = false;
                    location.href = result.data.data.redirect_to;


                }).catch(error => {
                    let errorArr = [];
                    this.runningTokenStringSize = 0;
                    this.resetCursorPosition();
                    this.$set(this.$data, "tokenPieces", []);
                    this.errorMessages = Object.keys(error.response.data.errors).map(errorKey => {

                        return error.response.data.errors[errorKey].map(err => {
                            return err;
                        });
                    });
                    if (this.showRetries && (this.maxAttempts >= this.currentAttempts)) {

                        window.refresh();

                    }
                    this.isSubmittingMfaVerification = false;

                });

            }

        },
        props: {
            mfaSubmissionUri: {
                type: String,
                required: true
            },
            email: {
                type: String,
                required: true
            },
            verifyTokenUri: {
                type: String,
                required: false,
                default: null
            },
            currentAttempts: {
                type: Number,
                required: false,
                default: null
            },
            maxAttempts: {
                type: Number,
                required: false,
                default: null
            }
        }
    }
</script>
