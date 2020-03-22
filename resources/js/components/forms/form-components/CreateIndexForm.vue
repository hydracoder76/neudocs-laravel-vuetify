<template>
	<div>
		<neu-form dusk="neu-create-index-form" :is-modal="true" @neu-submit="submitNewIndex" modal-size="lg">
			<template slot="neu-form-modal-title">
				Create new indexes for a project
			</template>

			<template slot="neu-modal-form-content">
				<div dusk="neu-index-form-modal">

					<p class="text-danger" v-for="error in validationErrors">{{ error }}</p>

					<b-form-group :label-cols="4" label="Index Name" horizontal
								  label-for="index-name" label-text-align="right"
								  id="index-name-group">
						<b-input-group>

							<b-form-input id="index-name"
										  type="text"
										  class="neu-input"
										  v-model="indexName"></b-form-input>
							<span class="sr-only">{{ indexNameDesc }}</span>
							<span class="col-2">
								<i @click="indexName = ''" class="fas fa-times-circle"></i>
								<i id="index-name-help" :class="infoIcon"></i>
							</span>
							<b-tooltip target="index-name-help" placement="right">{{ indexNameDesc }}</b-tooltip>
						</b-input-group>
					</b-form-group>

					<b-form-group :label-cols="4" label="Index Internal Name" horizontal
								  label-for="index-int-name" label-text-align="right"
								  id="index-int-name-group">
						<b-input-group>

							<b-form-input id="index-int-name"
										  type="text"
										  class="neu-input"
										  v-model="indexIntName"></b-form-input>
							<span class="sr-only">{{ indexIntNameDesc }}</span>
							<span class="col-2">
								<i @click="indexIntName = ''" class="fas fa-times-circle"></i>
								<i id="index-int-name-help" :class="infoIcon"></i>
							</span>
							<b-tooltip target="index-int-name-help" placement="right">{{ indexIntNameDesc }}</b-tooltip>
						</b-input-group>
					</b-form-group>

					<b-form-group :label-cols="4" label="Index Description" horizontal
								  label-for="index-desc" label-text-align="right"
								  id="index-desc-group">
						<b-input-group>
							<b-form-input id="index-desc"
										  type="text"
										  class="neu-input"
										  v-model="indexDesc"></b-form-input>
							<span class="sr-only">{{ indexDescDesc }}</span>
							<span class="col-2">
								<i @click="indexDesc = ''" class="fas fa-times-circle"></i>
								<i id="index-desc-help" :class="infoIcon"></i>
							</span>
							<b-tooltip target="index-desc-help" placement="right">{{ indexDescDesc }}</b-tooltip>
						</b-input-group>
					</b-form-group>


				</div>

			</template>

		</neu-form>
		<neu-shadow-backdrop id="neu-create-index-shadow" :show="isSubmitting"
							 :shadow-text="submittingText"
							 :shadow-icon="submittingIcon"></neu-shadow-backdrop>
	</div>
</template>
<style scoped>
	#index-type-select {
		padding-left: 0;
	}
</style>
<script>

	import NeuForm from "../NeuForm";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
	import bTooltip from "bootstrap-vue/es/components/tooltip/tooltip";
	import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
	import bFormRadio from "bootstrap-vue/es/components/form-radio/form-radio";
	import bFormRadioGroup from "bootstrap-vue/es/components/form-radio/form-radio-group";
	import neuInputUtils from "../../mixins/neu-input-utils";
	import NeuShadowBackdrop from "../../util/NeuShadowBackdrop";
	import axios from "axios";

	export default {
		created() {
			this.$on("neu-item-selected", value => {
				if (value !== null) {
				//	this.setIndexTypeName(value);
				}
			});
			this.$root.$on("bv::hide::modal", () => {
				this.indexDesc = "";
				this.indexName = "";
				this.indexIntName = "";
			});
		},
		data() {
			return {
				indexesCreated: [],
				indexName: "",
				indexIntName: "",
				indexDesc: "",
				indexVal: "",
				indexMes: "",
				indexNameDesc: "The name of the index as it will appear when rendered, the label",
				indexIntNameDesc: "The stored name of the index; must be unique",
				indexDescDesc: "A description of the index that can be used as a help message",
				indexValDesc: "A regexp expression that will be used to validate index input",
				indexValClassNameDesc: "A specially created validation class (advanced)",
				indexMesDesc: "Returned error message when validation fails",
				infoIcon: "fas fa-info-circle",
				isSubmitting: false,
				submittingText: "Adding index",
				submittingIcon: "cog",
				errors: [],
			};
		},
		methods: {
			submitNewIndex() {
				// TODO: for some reason the shadow won't hide when it's supposed to. have a look
				axios.post(this.indexSubmissionUri, {
					index_name: this.indexName,
					index_int_name: this.indexIntName,
					index_description: this.indexDesc,
					project_id: this.projectId
				}).then(result => {
					this.isSubmitting = false;
					this.$root.$emit("bv::hide::modal", "neu-form-modal");
					this.errors = [];
					this.$emit('new-index');
				}).catch(error => {
					this.isSubmitting = false;
					this.errors = error.response.data.errors;
				});
			},
			setIndexTypeName(name) {
				if (name !== "") {
					this.selectedIndexTypeName = name;
				}
			}
		},
		computed: {
			validationErrors() {
				const errors = [];
				if (this.errors instanceof Object) {

					Object.keys(this.errors).forEach(key => {

						this.errors[key].forEach(singleError => {
							errors.push(singleError);
						});

					});
				}
				return errors;
			},
		},
		mixins: [neuInputUtils],
		components: {
			NeuForm,
			bFormGroup,
			bFormInput,
			NeuShadowBackdrop,
			bInputGroup,
			bTooltip,
			bFormSelect,
			bFormRadio,
			bFormRadioGroup
		},
		props: {
			projectId: {
				type: String,
				required: true
			},
			indexSubmissionUri: {
				type: String,
				required: true
			},
		}
	}
</script>