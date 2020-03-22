<template>
	<div :data-position="position">
		<b-form-group :label="indexDisplayName">

			<b-form-textarea v-model="textValue" :name="indexInternalName"
							 :rows="rows"
							 :max-rows="maxRows"
							 :placeholder="placeholder"></b-form-textarea>
		</b-form-group>
	</div>
</template>

<script>

	import bFormTextarea from "bootstrap-vue/es/components/form-textarea/form-textarea";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import neuInputEvent from "../../mixins/neu-input-event";
	export default {
		data() {
			return {
				textValue: "",
				rows: 3,
				maxRows: 6,
				placeholder: "Enter text..."
			};
		},
		watch: {
			textValue() {
				this.$emit("neu-input-entered", this.getEventObj("textarea", this.textValue));
			}
		},
		mixins: [neuInputEvent],
		components: {
			bFormTextarea, bFormGroup
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