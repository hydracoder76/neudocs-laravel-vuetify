<template>
	<div :data-position="position" class="row align-content-start">
		<b-form-group :label="indexDisplayName">

			<vue-multi-select v-model="values" :name="indexInternalName"
							  :options="options"
							  :select-options="multiSelectData"></vue-multi-select>
		</b-form-group>
	</div>
</template>

<script>

	import neuInputEvent from "../../mixins/neu-input-event";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import vueMultiSelect from "vue-multi-select";

	export default {
		data() {
			return {
				values: [],
				placeholder: "Select multiple items",
				options: {
					multi: true
				}
			};
		},
		mixins: [neuInputEvent],
		watch: {
			values() {
				this.$emit("neu-input-entered", this.getEventObj("multi", this.values));
			}
		},
		props: {
			multiSelectData: {
				type: [Array, Object],
				required: false,
				default: () => { return [{name:"sample 1"}]; }
			}
		},
		components: {
			vueMultiSelect, bFormGroup
		},
		props: {
			position: {
				type: Number,
				required: false,
				default: 0
			},
			indexDisplayName: {
				type: String,
				required: false,
				default: ""
			},
			indexInternalName: {
				type: String,
				required: false,
				default: ""
			}

		}
	}
</script>