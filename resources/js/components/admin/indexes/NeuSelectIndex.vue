<template>
	<div :data-position="position">
		<b-form-group :label="indexDisplayName">
			<neu-data-dropdown :placeholder="placeholder" :name="indexInternalName" @neu-item-selected="itemSelected"></neu-data-dropdown>
		</b-form-group>
	</div>
</template>

<script>

	import NeuDataDropdown from "../../util/NeuDataDropdown";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import neuInputEvent from "../../mixins/neu-input-event";

	export default {
		data() {
			return {
				placeholder: "Select and item..."
			};
		},
		mixins: [neuInputEvent],
		methods: {
			itemSelected(item) {
				this.$emit("neu-input-entered", this.getEventObj("select", item));
			}
		},
		components: {
			NeuDataDropdown, bFormGroup
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