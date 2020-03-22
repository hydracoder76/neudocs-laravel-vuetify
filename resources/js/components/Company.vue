<template>
	<div>
	<neu-form :submission-uri="submitTo" >
		<neu-table title="Company Configuration"
				   tabname="Company"
				   type="edit"
				   ref="status"
				   :data-neu-table="dataTable"
				   :columns="dataColumns"
				   :company-uri="companyUri"
				   :company-load-uri="companyLoad"
				   :no-local-sorting="true"
				   :no-local-filtering="true"
				   @add-ok="add"
				   @edit-ok="edit"
				   @delete-ok="deletefn"
				   @page-change="updatePageChange"
				   @neu-sort-changed="companiesSort"
				   @filter-ok="companyFilter"
				   @clear-filter-ok="clearFilter"
				   class="vm-margin"
		>
		</neu-table>
	</neu-form>
		<neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
							 shadow-icon="cog"></neu-shadow-backdrop>
	</div>
</template>

<script>
	import NeuTable from "./util/NeuTable";
	import NavBar from "./NavBar";
	import axios from "axios";
	import NeuForm from "./forms/NeuForm";
	import neuInputUtils from "./mixins/neu-input-utils";
	import NeuShadowBackdrop from "./util/NeuShadowBackdrop";

	export default {
		name: "EditableCompanyTable",
		components: {
			NeuTable, NavBar, NeuForm, NeuShadowBackdrop
		},
		mixins: [neuInputUtils],
		props: {
			companyLoad: {
				type: String,
				required: true
			},
			companyUri: {
				type: String,
				required: true
			},
			submitTo: {
				type: String,
				required: false,
				default: ""
			},
			companySearch: {
				type: String,
				required: true
			}
		},
		mounted(){
			// TODO: there is no reason to maintain context. if there is a problem when this is not here, please fix that problem and don't skirt around it
			const self = this;
			axios.all([
				axios.get(this.companyLoad)
			]).then(axios.spread(function (companiesResult) {
				self.dataTable = companiesResult.data.data[0].data;
				self.dataTable.total = companiesResult.data.data[0].total;
			}));
		},
		methods: {
			populateDataColumnOptions(data, key, attributeName){
				for (let i in data) {
					this.dataColumns.forEach(function(el2){
						if(el2.key === key) {
							el2.options.push({
								value: data[i].id,
								text: (attributeName === null ) ? data[i].name : data[i].company_name
							});
						}
					});
				}
			},
			add(data) {
				this.waitingIndicator = true;
				axios.post(this.companyUri, data).then(result=>{
					// the result is coming in as a json string named company id?
					const resultObj = JSON.parse(result.data.data.company_id);
					this.dataTable.push({company_name: resultObj.company_name, id: resultObj.id});
					this.waitingIndicator = false;
					this.$refs.status.showAlert([result.data.message], "success");
				}).catch(error => {
					this.waitingIndicator = false;
					this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
				});
			},
			edit(data) {
				for (let i = 0; i < this.dataTable.length; i++) {
					if (this.dataTable[i].id === data.id) {
						this.waitingIndicator = true;
						axios.put(this.concatUriAndId(this.companyUri, data.id), data).then(result =>{
							this.dataTable[i] = data[i];
							this.waitingIndicator = false;
							this.$refs.status.showAlert([result.data.message], "success");
						}).catch(error => {
							this.waitingIndicator = false;
							this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
						});
					}
				}
			},
			deletefn(data) {
				for (let i = 0; i < this.dataTable.length; i++) {
					for (let j = 0; j < data.length; j++) {
						if (this.dataTable[i].id === data[j].id) {
							this.waitingIndicator = true;
							axios.delete(this.concatUriAndId(this.companyUri, data[j].id)).then(({data})=>{
								this.dataTable.splice(i, 1);
								this.waitingIndicator = false;
								this.$refs.status.showAlert(["Company deleted successfully"], "success");
							}).catch(error => {
								this.waitingIndicator = false;
								this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
							});

						}
					}
				}
			},
			concatUriAndId(uri, id) {
				return uri + "/" + id;
			},
			updatePageChange(page) {
				if (!this.isSort && !this.isFilter) {
					this.waitingIndicator = true;
					axios.get(this.companyUri + '?page=' + page).then(companiesResult => {
						this.dataTable = companiesResult.data.data[0].data;
						this.dataTable.total = companiesResult.data.data[0].total;
						this.dataTable.currentPage = page;
						this.waitingIndicator = false;
					}).catch(error => {
						this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
						this.waitingIndicator = false;
					});
				}
				else{
					this.companiesSearchApi(page);
				}
			},
			companiesSort(sortObj){
				if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
					this.isSort = true;
					this.sortBy = sortObj.sortBy;
					this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
					this.companiesSearchApi(1);
				}
			},
			companyFilter(keyword){
				this.isFilter = true;
				this.keyword = keyword;
				this.sortBy = "";
				this.companiesSearchApi(1);
			},
			clearFilter(){
				this.isFilter = false;
				this.isSort = false;
				this.sortBy = "";
				this.keyword = "";
				this.updatePageChange(1);
			},
			companiesSearchApi (page){
				this.waitingIndicator = true;
				axios.get(this.queryUrlBuilder(this.companySearch, {page : page, order : this.sortDesc, keyword : this.keyword, sortBy: this.sortBy})).then(result => {
					this.dataTable  = result.data.data.result;
					this.dataTable.total  = result.data.data.total;
					this.dataTable.currentPage = page;
					this.waitingIndicator = false;
				}).catch(error => {
					this.waitingIndicator = false;
					this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
				});
			}
		},
		data() {
			return {
				dataTable: [
				],
				dataColumns: [
					{key: "company_name", label: "Company Name", "visible" : true, "editable" : true, sortable: true, sortDirection: "asc","type":"TEXT"},
					{key: "company_access_type", label: "Business Role", sortable: false,"visible" : false, "editable" : false, "type":"DROPDOWN",
						"options" :[
							{ value : "client", text : "Client"},
							{ value : "neubus", text : "Neubus"},
							{ value : "it", text : "IT"}
						]
					},
					{key: "company_contact", label: "Company Contact", "visible" : false, "editable" : false, sortable: false,"type":"DROPDOWN",
						"options" : [
						]
					},
					{key: "company_contact_name", label: "Company Contact", "visible" : false, "editable" : false, sortable: false,"type":"DROPDOWN"},
					{key: "actions", label: "Actions", visible : true, sortable: false}
				],
				isSort: false,
				sortBy: "",
				sortDesc: false,
				isFilter: false,
				keyword: "",
				waitingIndicator: false,
				waitingText: ""
			};
		}
	}
</script>
