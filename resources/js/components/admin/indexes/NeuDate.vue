<template>
	<div :data-position="position">
		<b-form-group :labe="indexDisplayName">
			<b-form-input class="neu-input" :name="indexInternalName"
						  type="date"
						  @input="dateEntered"
						  :placeholder="placeholderText"
						  v-model="dateValue"></b-form-input>
		</b-form-group>
	</div>
</template>

<script>

	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bFormGroup from "bootstrap-vue/es/components/form-input/form-input";
	import neuInputEvent from "../../mixins/neu-input-event";

	export default {
		data() {
			return {
				dateValue: "",
				placeholderText: "mm/dd/yyyy"
			};
		},
		mixins: [neuInputEvent],
		methods: {
			dateEntered() {

				this.$emit("neu-input-entered", this.getEventObj("date", this.dateValue));
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
			neuDateFieldLabel: {
				type: String,
				required: false,
				default: ""
			},
			neuDateFieldId: {
				type: String,
				required: true
			},
			useMoment: {
				type: Boolean,
				required: false,
				default: false
			}

		}
	}
</script>