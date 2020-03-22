<template>
	<div :data-position="position" class="neu-row justify-content-between">
		<b-form-group :label="indexDisplayName">

			<b-form-input class="neu-input col-5" :name="indexInternalName"
						  :placeholder="placeholderText"
						  v-model="firstDate"
						  type="date"
						  @input="rangeEntered"></b-form-input>

			<b-form-input class="neu-input col-5" :name="indexInternalName"
						  :placeholder="placeholderText"
						  v-model="secondDate"
						  type="date"
						  @input="rangeEntered"></b-form-input>
		</b-form-group>
	</div>
</template>

<script>

	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import neuInputEvent from "../../mixins/neu-input-event";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	export default {
		data() {
			return {
				firstDate: "",
				secondDate: "",
				placeholderText: ""
			};
		},
		mixins: [neuInputEvent],
		methods: {
			rangeEntered() {
				this.$emit("neu-input-entered", this.getEventObj("text",
					{firstDate: this.firstDate, secondDate: this.secondDate}));
			}
		},
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