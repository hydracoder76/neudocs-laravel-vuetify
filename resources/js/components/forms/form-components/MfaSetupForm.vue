<template>
    <div id="neu-mfa-setup-cont">

        <p v-if="errorMessage !== ''">{{ errorMessage }}</p>

        <transition mode="out-in" name="slide-fade">
            <div>
                <div v-if="showQrSetup" class="panel-heading text-center">Set up Multi-Factor Authentication</div>
                <div v-if="showQrSetup" id="neu-mfa-qr-setup" class="col-md-10 neu-row offset-md-2">
                    <div class="col-md-6">
                        <p>Please set up and confirm multi-factor authentication with a separate device. Steps are as
                            follows:</p>
                        <ol>
                            <li>If you don't already, you will need to have the Google Authenticator app installed on a
                                mobile device.
                                <blockquote class="neu-footnote">If you elect to use an Android device, search for
                                    Google
                                    Authenticator in Google Play.
                                    <br/>https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator

                                    <br/>If you elect to use an Apple device, search for Google Authenticator in the App
                                    Store.
                                    <br/>https://itunes.apple.com/us/app/google-authenticator/id388497605
                                </blockquote>
                            </li>
                            <li>Launch the Google Authenticator app on your mobile device.</li>
                            <li>If this is your first time using the Google Authenticator app, you
                                may already be on the "Add an account" screen. If so, move to step 4. If not,
                                tap the "Add Account" button.
                            </li>
                            <li>When on the "Add an account" screen, tap "Scan a barcode".</li>
                            <li>Use your mobile device's camera, and the interface inside the Google Authenticator app,
                                to scan the on-screen 2-D barcode below.
                            </li>
                            <li>The app should display a screen saying "Account added", with a large six-digit number,
                                and the words "Neubus SRM (Neubus SRM)".<br/>
                                <em>You'll need to return to this Google Authenticator app whenever you need to log in
                                    to
                                    neuDocs SRM, to get a code to authenticate your valid access of the SRM system. In
                                    order to
                                    ensure that Google Authenticator is configured correctly, now we'll test the
                                    authentication
                                    to ensure SRM is linked up correctly to your Google Authenticator account.</em>
                            </li>
                            <li>Click "Continue" below.</li>
                        </ol>
                    </div>
                    <div class="col-md-5">
                        <neu-form id="neu-mfa-qr-form" key="qr" v-if="showQrSetup" dusk="neu-mfa-setup-form-qr"
                                  @neu-submit="moveToMfaTest"
                                  :regular-form-submit="false"
                                  :submission-uri="mfaSetupUri">
                            <div class="neu-row justify-content-center">
                                <span class="sr-only">Scan the QR code with Google Authenticator on your phone</span>
                                <img :src="imgUrl" v-if="showQrCode" alt="MFA QR Code" id="neu-mfa-qr"/>
                                <div v-if="showSecret" id="neu-secret-div">
                                    Secret Key: {{ secret }}
                                </div>
                            </div>
                            <b-form-group>

                                <div class="neu-btn-cont neu-row justify-content-center">
                                    <neu-button btn-size="md" btn-type="cancel"
                                                btn-text="Logout" btn-dusk="neu-mfa-verify-logout-btn"
                                                @neu-btn-click="cancelMfaSetup($event, false)">
                                        <p class="neu-footnote text-center">(I&apos;m not ready)</p>
                                    </neu-button>
                                    <neu-button btn-type="confirm" btn-size="md" btn-text="Continue"
                                                btn-dusk="neu-mfa-verify-btn"
                                                @neu-btn-click="moveToMfaTest">
                                        <!-- for text centering in the first element -->
                                        <p class="neu-footnote text-center">&nbsp;</p>
                                    </neu-button>
                                </div>
                                <div class="neu-btn-cont neu-row justify-content-center">
                                    <neu-button btn-size="md" btn-type="confirm"
                                                btn-text="Failed to read barcode?" btn-dusk="neu-mfa-verify-secret-btn"
                                                @neu-btn-click="getQrCode(true)">
                                    </neu-button>
                                </div>

                            </b-form-group>

                        </neu-form>
                    </div>
                </div>
            </div>
        </transition>
        <transition mode="out-in" name="slide-fade">
            <div>
                <div v-if="showCodeVerify" class="panel-heading text-center">Verify your MFA Token</div>
                <div class="col-md-10 offset-md-2" v-if="showCodeVerify">
                    <div id="neu-mfa-setup-verify-form" class="neu-row">
                        <div class="col-md-6">
                            <p>Please set up and confirm multi-factor authentication with a separate device. Steps are
                                as
                                follows:</p>

                            <p><em>You'll need to return to this Google Authenticator app whenever you need to log in to
                                neuDocs
                                SRM, to get a code to authenticate your valid access of the SRM system. In order to
                                ensure
                                that
                                Google Authenticator is configured correctly, now we'll test the authentication to
                                ensure
                                SRM is
                                linked up correctly to your Google Authenticator account.</em></p>

                            <p>In the blanks below, enter the six-digit code from the app. Be aware the code in the app
                                changes
                                twice a minute.</p>

                            <p><em>If the code is correct, a green checkmark will appear to confirm your valid entry.
                                After
                                clicking "Done", you will need to log back in with your username and password, and then
                                enter
                                your code from your Google Authenticator app.</em></p>

                            <p>Click "Done" and then log back in.</p>
                        </div>
                        <div class="col-md-4">
                            <neu-form key="verify" id="neu-mfa-setup-form-verify"
                                      dusk="neu-mfa-setup-form-verify"
                                      @keydown.enter.prevent="submitMfaSetup"
                                      :regular-form-submit="false"
                                      :submission-uri="mfaSetupUri">

                                <b-form-group label="" label-size="lg" label-for="neu-mfa-token">

                                    <div class="neu-btn-cont">

                                        <div class="neu-row justify-content-center">

                                            <neu-button v-if="isVerified" btn-size="md" btn-type="confirm"
                                                        btn-text="Done" btn-dusk="neu-mfa-verify-submit-btn"
                                                        @neu-btn-click="submitMfaSetup">
                                                <p v-if="isVerified" class="neu-footnote text-center">(Need to
                                                    relog)</p>
                                            </neu-button>
                                            <neu-button btn-size="md" btn-type="cancel"
                                                        btn-text="Start Over" btn-dusk="neu-mfa-verify-redo-btn"

                                                        @neu-btn-click="cancelMfaSetup($event, true)">
                                                <p class="neu-footnote text-center">(Repeat)</p>
                                            </neu-button>

                                        </div>

                                    </div>
                                    <div class="col-md-12 neu-row">

                                        <div class="col-md-6" id="mfa-setup-field-div">

                                            <input :id="fieldIdPrefix + index" autocomplete="false" type="text"
                                                   :tabindex="index + 1" v-for="(field, index) in numLinkedFields"
                                                   v-model="tokenPieces[index]" :key="index"
                                                   @keydown.enter.self="submitMfaSetup" maxlength="1"
                                                   @keyup.self="tokenPieceEntered(index, $event)"
                                                   dusk="neu-verify-mfa-token-input"
                                                   class="neu-input neu-input-single"/>


                                            <div class="mfa-verify-indicator col-md-6">
                                                <span class="sr-only">Valid Token</span>
                                                <font-awesome-icon v-if="isVerified" far
                                                                   :icon="validIcon" :size="iconSize"
                                                                   class="neu-green-icon"></font-awesome-icon>

                                                <span class="sr-only">Invalid Token</span>
                                                <font-awesome-icon v-if="isVerified === false" far
                                                                   :icon="invalidIcon" :size="iconSize"
                                                                   class="neu-red-icon"></font-awesome-icon>
                                                <font-awesome-icon v-if="isVerifying" :icon="verifyingIcon"
                                                                   :size="iconSize" spin></font-awesome-icon>

                                            </div>
                                        </div>
                                    </div>
                                </b-form-group>

                            </neu-form>
                        </div>
                    </div>
                </div>
            </div>

        </transition>

        <neu-shadow-backdrop :show="isCancelling"></neu-shadow-backdrop>
        <b-modal dusk="modalInfo" v-model="modalInfo" title="2fA Alternative Setup" :ok-only="true">
            <p class="my-4 modal-info">The code has been refreshed and you may now see the secret key which you can
                manually enter into the Google Authenticator app.
                Tap the + button in the mobile app to start adding a new entry, then tap 'Enter a provided key'.
                Then, in the respective two fields on that resulting screen, enter your email address and enter the
                Secret Key we're about to generate.
                For the dropdown in the app, select "Time based".</p>
        </b-modal>

    </div>
</template>

<style scoped>

    #neu-mfa-qr-form {
        position: relative;
    }

    #neu-mfa-setup-cont {
        min-height: 600px;
    }

    .slide-fade-enter-active {
        transition: all .3s ease;
    }

    .slide-fade-leave-active {
        transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0;
    }

    #neu-secret-div {
        text-align: center;
    }

    .modal-info {
        margin-left: 25px;
        margin-right: 25px;
    }

    #mfa-setup-field-div {
        min-width: 300px;
    }
</style>

<script>

    import axios from "axios";
    import NeuForm from "../../forms/NeuForm";
    import NeuButton from "../../util/NeuButton";
    import NeuShadowBackdrop from "../../util/NeuShadowBackdrop";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
    import {faTimesCircle, faCheckCircle, faSpinner} from "@fortawesome/free-solid-svg-icons";
    import neuInputLinked from "../../mixins/neu-input-linked";
    import bModal from "bootstrap-vue/es/components/modal/modal";

    export default {

        mounted() {

            this.getQrCode(false);

            this.$on("neu-linked-input", event => this.continueToVerify());

        },
        data() {
            return {
                imgUrl: "",
                errorMessage: "",
                fieldIdPrefix: "neu-mfa-verify-",
                showQrCode: false,
                showQrSetup: true,
                showCodeVerify: false,
                isCancelling: false,
                cancellingText: "",
                verificationToken: "",
                isVerified: null,
                isVerifying: false,
                validIcon: faCheckCircle,
                invalidIcon: faTimesCircle,
                verifyingIcon: faSpinner,
                iconSize: "2x",
                showSecret: false,
                secret: "",
                modalInfo: false
            };
        },
        mixins: [neuInputLinked],
        methods: {
            getQrCode(alt) {
                axios.get(this.mfaSetupUri, {
                    params: {
                        email: this.email,
                        alt: alt
                    }
                }).then(result => {
                    this.imgUrl = result.data.data.qr_img;
                    this.verificationToken = result.data.data.verification_token;
                    this.showQrCode = true;
                    if (alt) {
                        this.secret = result.data.data.secret;
                        this.showSecret = true;
                        this.modalInfo = true;
                    }
                }).catch(error => {
                    this.errorMessage = error.response.data.errors;
                    this.imgUrl = "";
                    this.showQrCode = false;
                });
            },
            deleteToken(index) {
                this.tokenPieces.splice(index, 1, null);
            },
            cancelMfaSetup(event, goBackToQr) {
                this.isCancelling = true;
                axios.delete(this.mfaSetupUri, {
                    params: {
                        email: this.email,
                        verify_token: this.verificationToken
                    }
                }).then(result => {
                    this.isCancelling = false;
                    if (goBackToQr) {
                        this.showQrCode = false;
                        this.moveBackToQr();
                    } else {
                        location.href = result.data.data.redirect_to;
                    }
                }).catch(error => {
                    this.isCancelling = false;
                });
            },
            submitMfaSetup() {
                axios.post(this.mfaSetupUri, {
                    email: this.email,
                    token: this.tokenPieces.join(""),
                    confirm: true
                }).then(result => {
                    location.href = result.data.data.redirect_to;
                }).catch(error => {
                    this.runningTokenStringSize = 0;
                    this.resetCursorPosition();
                    this.$set(this.$data, "tokenPieces", []);
                });
            },
            checkTokenViaApi() {
                this.isVerifying = true;
                axios.post(this.mfaSetupUri, {
                    email: this.email,
                    token: this.tokenPieces.join(""),
                    confirm: false
                }).then(result => {
                    this.isVerifying = false;
                    this.isVerified = true;
                }).catch(error => {
                    this.isVerifying = false;
                    this.isVerified = false;
                    this.runningTokenStringSize = 0;
                    this.resetCursorPosition();
                    this.$set(this.$data, "tokenPieces", []);
                });
            },
            continueToVerify() {
                if (this.runningTokenStringSize === this.numLinkedFields) {
                    this.checkTokenViaApi();
                } else {
                    this.isVerifying = false;
                    this.isVerified = null;
                }
            },
            restartSetup() {
                this.isCancelling = true;
                this.isVerifying = false;
                this.isVerified = null;
                this.resetLinkedFields();

            },
            moveToMfaTest() {
                this.showQrSetup = false;
                this.showCodeVerify = true;
            },
            moveBackToQr() {
                this.isCancelling = false;
                this.isVerifying = false;
                this.isVerified = null;
                this.showCodeVerify = false;
                this.showQrSetup = true;
                this.showQrCode = false;
                this.resetLinkedFields();
                this.showSecret = false;
                this.secret = "";
                this.getQrCode(false);
            }
        },
        props: {
            email: {
                type: String,
                required: true
            },
            mfaSetupUri: {
                type: String,
                required: false,
                default: null
            },
            mfaVerifyUri: {
                type: String,
                required: false,
                default: null
            }
        },
        components: {
            NeuForm, NeuButton, bFormGroup, NeuShadowBackdrop, FontAwesomeIcon, bModal
        }
    };
</script>
