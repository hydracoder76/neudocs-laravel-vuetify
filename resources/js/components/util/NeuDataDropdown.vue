<template>
	<div class="neu-data-dropdown-cont col-12-4 col-xs-12 p-0">
		<vue-single-select dusk="vue-single-select" v-model="selectedItem"
						   :option-key="valueKey"
						   :option-label="labelKey"
						   :get-option-description="labelKeys"
						   :get-option-value="valueKeys"
						   :options="items"
						   :placeholder="placeholder"
						   :input-id="inputId"
						   :initial="initial"
						   :max-results="1000"
						   ref="selectedValue"
						   @input="changeProject($parent)"></vue-single-select>
	</div>
</template>

<script>

	import VueSingleSelect from "vue-single-select";
	import axios from "axios";

	export default {
		data() {
			return {
				selectedItem: {},
				items: [],
			};
		},
		mounted() {
			if (this.dataSourceUri !== null) {

				axios.get(this.dataSourceUri).then(result => {
					const lab = this.labelKey;
					const val = this.valueKey;
					this.items = result.data.data.map((item, index) => {
						const result = {};
						result[this.labelKey] = item.label;
						result[this.valueKey] = item.value;

                        // if no default project , default project = topmost selection
                        if (index === 0 && ( this.initial === null || this.initial === "")) {
                            this.selectedItem = {};
                            this.selectedItem[this.labelKey] = item.label;
                            this.selectedItem[this.valueKey] = item.value;
                            this.selectedItem['firstSelection'] = true;
                        }

                        if (item.value === this.initial) {
                            this.selectedItem = {};
                            this.selectedItem[this.labelKey] = item.label;
                            this.selectedItem[this.valueKey] = item.value;
                        }
						return result;
					});
				}).catch(error => {

				});
			}
			else {
				this.items = this.values;
			}
		},
		watch: {
			initial(curr) {
				if (curr === null) {
					this.selectedItem = "";
				}
			}
		},
		methods: {
            changeProject(parent) {
                if (this.selectedItem !== null && this.selectedItem.hasOwnProperty('value')) {
                    parent.$emit('neu-item-selected', this.selectedItem);
                    this.sendUpdatedData();
                }
            },
            sendUpdatedData() {
                const defaultProjectUpdated = (
                    this.updateDefaultProjectUrl !== "" &&
                    this.selectedItem[this.valueKey] !== this.initial &&
                    !this.selectedItem.hasOwnProperty("firstSelection")
                );

                if(defaultProjectUpdated) {
                    axios.post(this.updateDefaultProjectUrl, {
                        projectId: this.selectedItem[this.valueKey]
                    })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        });
                }
            },
			labelKeys(option) {
				return option[this.labelKey];
			},
			valueKeys(option) {
				return option[this.valueKey];
			}
		},
		props: {
			initial: {
				required: false,
				default: ""
			},
			dataSourceUri: {
				type: String,
				required: false,
				default: null
			},
			values: {
				type: Array,
				required: false,
				default: () => { return []; }
			},
			labelKey: {
				type: String,
				required: false,
				default: "label"
			},
			valueKey: {
				type: String,
				required: false,
				default: "value"
			},
			placeholder: {
				type: String,
				required: false,
				default: ""
			},
			inputId: {
				type: String,
				required: false,
				default: "single-select"
			},
            updateDefaultProjectUrl:{
                type: String,
                required: false,
                default: ""
            },
		},
		components: {
			VueSingleSelect
		}
	}
</script>
