<template>
	<div :data-position="position">
		<b-form-group :label="indexDisplayName">

			<b-form-input :name="indexInternalName" class="neu-input"
						  v-model="inputValue"
						  :placeholder="placeholder"
						  @input="textEntered"></b-form-input>
		</b-form-group>
	</div>
</template>

<script>

	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import neuInputEvent from "../../mixins/neu-input-event";

	export default {
		data() {
			return {
				inputValue: "",
				placeholder: "Enter text..."
			}
		},
		methods: {
			textEntered() {
				this.$emit("neu-input-entered", this.getEventObj("text", this.inputValue));
			}
		},
		mixins: [neuInputEvent],
		components: {
			bFormInput, bFormGroup
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