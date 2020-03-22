<template>
	<div>
		<slot></slot>



			<p v-if="errorMsg" class="text-danger">{{ errorMsg }}</p>
			<p v-for="(error, $index) in errors" :key="$index" class="text-danger">{{ error }}</p>
			<neu-form dusk="index-builder-form" v-if="showTable">
				<neu-table title="Index Types"
						   tabname="Index Type"
						   type="edit"
						   ref="status"
						   :data-neu-table="tableData"
						   :columns="dataColumns"
						   :no-local-sorting="false"
						   :no-local-filtering="true"
						   @add-ok="submitNewIndex"
						   @edit-ok="save"
						   @delete-ok="deleteIndex"
						   @page-change="updatePageChange"
						   class="vm-margin"
				>
				</neu-table>
			</neu-form>
		<!-- can this be removed? doesn't appear to be used anymore -->
	<!--	<create-index-form v-if="projectSelected.value" :project-id="projectSelected.value" :index-submission-uri="indexSubmitUri"
						   :opened="showAddIndex" @new-index="newIndexFunc"></create-index-form>-->

		<neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText" shadow-icon="cog"></neu-shadow-backdrop>
	</div>
</template>

<style scoped>
	.neu-fade-enter-active, .neu-fade-leave-active {
		transition: opacity .6s;
	}

	.neu-fade-enter, .new-fade-leave-to {
		opacity: 0;
	}

    .table-dropdown{
		width:auto;
	}
</style>

<script>

	import NeuForm from "../forms/NeuForm";
	import NeuTable from "../util/NeuTable";
	import NeuShadowBackdrop from "../util/NeuShadowBackdrop";
	import CreateIndexForm from "../forms/form-components/CreateIndexForm";
	import bButton from "bootstrap-vue/es/components/button/button";
	import bTable from "bootstrap-vue/es/components/table/table";
	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
	import NeuButton from "../util/NeuButton";
	import neuHandleProjectID from "../mixins/neu-handle-projectID";

	import axios from "axios";

	export default {
		created() {
			this.$on("neu-item-selected", value => {
				if (value !== null) {
					this.projectIDSelected(value);
				}
			});
		},
		mixins: [neuHandleProjectID],
		data() {
			return {
				tableData: [],
				tableName: "Indexing",
				showTable: false,
				waitingIndicator: false,
				waitingText: "Fetching Indexes",
				projectSelected: {},
				errors: [],
				errorMsg: false,
				isSubmitting: false,
				inEdit: [],
				isEditing: false,
				editData: {},
				dataColumns:
						[
							{
								key: "index_name",
								label: "Index Name",
								visible: true,
								sortable: true,
								sortDirection: "desc",
								editable: true,
								"type": "TEXT"
							},

							{
								key: "internal_name",
								label: "Internal Name",
								visible: true,
								sortable: false,
								editable: true,
								"type": "TEXT"
							},
							{
								key: "description",
								label: "Description",
								sortable: false,
								visible: true,
								editable: true,
								type: "TEXT"
							},
							{
								key: "created_at",
								label: "Created On",
								sortable: true,
								visible: true,
								editable: false,
								type: "TEXT"
							},
							{
								key: "actions",
								label: "Actions",
								visible: true,
								sortable: false
							}
						]
			};
		},
		methods: {

			showAddIndex() {
				this.$root.$emit("bv::show::modal", "neu-form-modal");
			},
			projectPartUri() {
				return this.indexUri + "/" + (this.projectId === null ? this.projectSelected.value : this.projectId);
			},
			updatePageChange(page) {
				this.retrieveIndexes(10, page);
			},
			retrieveIndexes(numToRetrieve, page) {
				this.waitingIndicator = true;
				axios.get(this.projectPartUri(), {
					query: {
						size: numToRetrieve,
						page: page
					}
				}).then(result => {
					this.inEdit = new Array(result.data.data.length).fill(false);
					this.tableData = result.data.data;
					this.waitingIndicator = false;
					this.errorMsg = false;
					this.errors = [];
				}).catch(error => {
					this.errorMsg = error.response.message;
					this.errors = error.response.data.errors;
					this.waitingIndicator = false;
				});
			},
			newIndexFunc(){
				this.retrieveIndexes(10, 1);
			},
			/* eslint camelcase:off */
			submitNewIndex(submissionEvent) {
				axios.post(this.indexSubmitUri, {
					index_name: submissionEvent.index_name,
					index_int_name: submissionEvent.internal_name,
					index_description: submissionEvent.description,
					project_id: this.projectSelected.value
				}).then(() => {
					this.isSubmitting = false;
					this.$root.$emit("bv::hide::modal", "neu-form-modal");
					this.errors = [];
					this.$emit("new-index");
					this.newIndexFunc();

				}).catch(error => {
					this.isSubmitting = false;
					this.errors = error.response.data.errors;
				});
			},
			deleteIndex(data) {
				// for indexes, there will only ever be one row deleted for now
				// however we want to validate anyway
				if (data.length === 1) {
					const indexID = data[0].id;
					axios.post(this.deleteUri, {id: indexID}).then(() => {
						this.retrieveIndexes(10, 1);
					}).catch(error => {
						this.errorMsg = error.response.message;
						this.errors = error.response.data.errors;
					});
				}

			},
			/* eslint no-undef:off */
			edit(item, index, target) {
				for (const name in this.tableNames) {
					if (this.tableNames.hasOwnProperty(name)) {
						Vue.set(this.editData, this.tableNames[name], this.tableData[index][this.tableNames[name]]);
					}
				}
				this.isEditing = true;
				Vue.set(this.inEdit, index, true);
			},
			cancel(index){
				Vue.set(this.inEdit, index, false);
				this.isEditing = false;
				this.editData = {};
			},
			save(item){
				this.isSubmitting = true;
				this.waitingIndicator = true;

				const params = {
					description: item.description,
					id: item.id,
					index_name: item.index_name,
					internal_name: item.internal_name
				};
				axios.post(this.editUri, params).then(() => {
					this.isSubmitting = false;
					this.waitingIndicator = false;
					this.retrieveIndexes(10, 1);
				}).catch(error => {

					this.errorMsg = error.response.message;
					this.errors = error.response.data.errors;
					this.isSubmitting = false;
					this.waitingIndicator = false;
				});
			}
		},
		props: {
			indexUri: {
				type: String,
				required: true
			},
			projectId: {
				type: String,
				required: false,
				default: null
			},
			indexSubmitUri: {
				type: String,
				required: true
			},
			deleteUri:{
				type: String,
				required: true
			},
			editUri:{
				type:String,
				required:true
			}
		},
		components: {
			NeuForm,
			NeuTable,
			NeuShadowBackdrop,
			CreateIndexForm,
			bButton,
			bTable,
			bFormInput,
			bFormSelect,
			NeuButton
		}
	}
</script>