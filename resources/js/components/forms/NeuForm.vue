<template>
	<div class="neu-form">
		<b-modal v-if="isModal"
				 :title="formHeader"
				 :id="neuFormModalId"
				 :size="modalSize"
				 @cancel="hideModal"
				 @ok.prevent="doSubmit"
				 :hide-footer="hideModalFooter"
				 :hide-header="hideModalHeader"
				 :ok-only="okOnlyModal"
				 :ok-title="okTitleModal"
				v-model="showModal">

			<template slot="modal-title">
				<slot name="neu-form-modal-title"></slot>
			</template>

			<template slot="modal-footer">
				<slot v-if="enableCustomFooter" name="neu-form-modal-footer"></slot>
				<slot v-else name="neu-form-default-footer">
					<div class="float-left">

						<neu-button btn-size="md" :btn-text="cancelBtnText" :btn-type="cancelBtnType"
									@neu-btn-click.self="hideModal"></neu-button>
					</div>
					<neu-button class="float-right" btn-size="md" :btn-text="confirmBtnText" :btn-type="confirmBtnType"
								@neu-btn-click.self="doSubmit"></neu-button>

				</slot>

			</template>

			<template slot="modal-ok">
				<slot name="neu-form-modal-ok"></slot>
				<slot></slot>
			</template>
			<template slot="modal-cancel">
				<slot name="neu-modal-form-cancel"></slot>
				<slot></slot>
			</template>

			<b-form :inline="isHorizontal" class="neu-modal-form" @submit.native.prevent="">
				<slot name="neu-modal-form-content"></slot>
			</b-form>
		</b-modal>
		<div v-else>
			<b-form class="neu-inline-form" @submit.native="doSubmit">
				<slot></slot>
			</b-form>
		</div>
	</div>
</template>

<script>

	import bModal from "bootstrap-vue/es/components/modal/modal";
	import bForm from "bootstrap-vue/es/components/form/form";
	import vBModal from "bootstrap-vue/es/directives/modal/modal";
	import NeuButton from "../util/NeuButton";

	export default {
		data() {
			return {
				showModal: false
			}
		},
		props: {
			hideModalFooter: {
				type: Boolean,
				required: false,
				default: false
			},
			hideModalHeader: {
				type: Boolean,
				required: false,
				default: false
			},
			okOnlyModal: {
				type: Boolean,
				required: false,
				default: false
			},
			okTitleModal: {
				type: String,
				required: false,
				default: "OK"
			},
			isHorizontal: {
				type: Boolean,
				required: false,
				default: false
			},
			isModal: {
				type: Boolean,
				required: false,
				default: false
			},
			modalSize: {
				type: String,
				required: false,
				default: "md"
			},
			formHeader: {
				type: String,
				required: false,
				default: ""
			},

			submissionUri: {
				type: String,
				required: false,
				default: "#"
			},
			regularFormSubmit: {
				type: Boolean,
				required: false,
				default: true
			},
			neuFormModalId: {
				type: String,
				required: false,
				default: "neu-form-modal"
			},
			confirmBtnText: {
				type: String,
				required: false,
				default: "Ok"
			},
			cancelBtnText: {
				type: String,
				required: false,
				default: "Cancel"
			},
			confirmBtnType: {
				type: String,
				required: false,
				default: "confirm"
			},
			cancelBtnType: {
				type: String,
				required: false,
				default: "cancel"
			},
			enableCustomFooter: {
				type: Boolean,
				required: false,
				default: false
			},
			displayModal: {
				type: Boolean,
				required: false,
				default: true
			}
		},
		methods: {
			hideModal() {
				this.$root.$emit("bv::hide::modal", this.newFormModalId);
				this.$emit("neu-reset-form-fields");
				this.showModal = false;
			},
			doSubmit(formData) {
				this.$emit("neu-submit", formData);
			}
		},
		components: {
			bModal, bForm, vBModal, NeuButton
		}
	}
</script>
