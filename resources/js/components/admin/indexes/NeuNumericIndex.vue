<template>
	<div :data-position="position">
		<b-form-group :label="indexDisplayName">

			<b-form-input :name="indexInternalName"
					type="text"
					class="neu-input"
					v-model="numericValue"
					:placeholder="placeholder"
					@input="inputEntered"
					v-numeric></b-form-input>
		</b-form-group>
	</div>
</template>

<script>

	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import neuInputEvent from "../../mixins/neu-input-event";
	import neuInputDirectives from "../../mixins/neu-input-directives";
	export default {
		data() {
			return {
				numericValue: null,
				placeholder: "Numeric..."
			};
		},
		methods: {
			inputEntered() {
				this.$emit("neu-input-entered", this.getEventObj("numeric", this.numericValue));
			}
		},
		mixins: [neuInputEvent, neuInputDirectives],
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