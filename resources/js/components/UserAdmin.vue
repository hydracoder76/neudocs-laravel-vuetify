<template>
	<div>
		<neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText" shadow-icon="cog"></neu-shadow-backdrop>
		<neu-form :submission-uri="submitTo" v-show="waitingIndicator === false">
			<neu-table title="Manage User"
					  tabname="User"
					  type="edit"
					  ref="status"
					  :data-neu-table="dataTable"
					  :columns="dataColumns"
					   :user-load-uri="userLoad"
					   :user-uri="userUri"
					   :company-load-uri="companyLoad"
					   :user-company="userCompany"
					   :user-role="userRole"
					   :no-local-sorting="true"
					   :no-local-filtering="true"
					   @add-ok="add"
					   @edit-ok="edit"
					   @delete-ok="deletefn"
					   @page-change="updatePageChange"
					   @neu-sort-changed="usersSort"
					   @filter-ok="userFilter"
					   @clear-filter-ok="clearFilter"
					   @neu-reset-password="resetPassword"
					  class="vm-margin"
			>
			</neu-table>
		</neu-form>
	</div>
</template>

<script>
	import NeuTable from "./util/NeuTable";
	import NeuShadowBackdrop from "./util/NeuShadowBackdrop";
	import NavBar from "./NavBar";
	import axios from "axios";
	import NeuForm from "./forms/NeuForm";
	import neuInputUtils from "./mixins/neu-input-utils";

	export default {

        props: {
            userLoad: {
                type: String,
                required: true
            },
            userUri: {
                type: String,
                required: true
            },
            submitTo: {
                type: String,
                required: false
            },
			companyLoad: {
				type: String,
				required: true
			},
			userCompany: {
				type: String,
				required: true
			},
			userRole: {
            	type: String,
				required: true
			},
			resetPassUri: {
            	type: String,
				required: true,
			},
			userSearch: {
            	type: String,
				required: true
			}
        },
		components: {
			NeuTable, NavBar, NeuForm, NeuShadowBackdrop
		},
		mixins: [neuInputUtils],
		mounted(){
        	this.initialLoad();
		},
		methods: {
        	initialLoad(){
        		this.waitingIndicator = true;
				if (this.userRole === "admin"){
					for (let dataColumn in this.dataColumns){
						if (this.dataColumns[dataColumn].key === "role"){
							this.dataColumns[dataColumn].options = [{ value: "client" , text: "Client"}, { value: "admin" , text: "Client Admin"}];
						}
					}
				}
				axios.all([
					axios.get(this.userLoad),
					axios.get(this.companyLoad)
				])
						.then(axios.spread((usersResult, companiesResult) => {
							this.dataTable  = usersResult.data.data.result.data;
							this.dataTable["total"]  = usersResult.data.data.total;
							this.dataTable["currentPage"] =  1;
							this.waitingIndicator = false;

							this.populateDataColumnOptions(companiesResult.data.data[0],"company_id","company_name");
						})).catch(() => {
					this.waitingIndicator = false;

				});
			},
			populateDataColumnOptions(data,key,attributeName){
				for (let i in data) {
					this.dataColumns.forEach(function(el2){
						if(el2.key === key) {
							el2.options.push({
								value: data[i].id,
								text: (attributeName == null ) ? data[i].name : data[i].company_name
							})
						}
					})
				}
			},
			getCompanyName(id){
        		let companyName = "";
				this.dataColumns.forEach(function(el2) {
					if (el2.key === "company_id") {
						el2.options.forEach(function (el3) {
							if (el3.value === id) {
								companyName = el3.text;
							}
						});
					}
				});
				return companyName;
			},
			addPasswordCopy(data) {
        		let dataCopy = data;
				if (!dataCopy.hasOwnProperty("password")) {
					dataCopy.password = "";
				}
				return dataCopy;
			},
			add: function (data) {
        		this.waitingText = "Creating user...";
				this.waitingIndicator = true;
        		let dataCopy = this.addPasswordCopy(data);
                axios.post(this.userUri,dataCopy).then(result=>{
                	dataCopy.id = result.data.data.id;
                	dataCopy.company_name = this.getCompanyName(dataCopy.company_id);
					this.dataTable.push(dataCopy);
					this.waitingIndicator = false;
                    this.$refs.status.showAlert([result.data.message], "success");
                }).catch(error => {
                    this.$refs.status.showAlert([error.response.data.message], "danger");
					this.waitingIndicator = false;
                });
			},
			edit: function (data) {
        		const dataCopy = data;
                for (let i = 0; i < this.dataTable.length; i++) {
                        if (this.dataTable[i].id === data.id) {
                                axios.put(this.concatUserUriAndId(this.userUri,data.id),data).then(result=>{
                                	dataCopy.id = result.data.data.id;
                                	dataCopy.company_name = this.getCompanyName(dataCopy.company_id);
                                	Vue.set(this.dataTable, i, dataCopy);
                                    this.$refs.status.showAlert([result.data.message], "success");
                                }).catch(error => {
									this.$refs.status.showAlert([error.response.data.message], "danger");
                                });
                            }
                    }
			},
			deletefn: function (data) {
				for (let i = 0; i < this.dataTable.length; i++) {
					for (let j = 0; j < data.length; j++) {
						if (this.dataTable[i].id === data[j].id) {
                            axios.delete(this.concatUserUriAndId(this.userUri,data[j].id)).then(({data})=>{
                                this.dataTable.splice(i, 1)
                                this.$refs.status.showAlert(["User deleted succesfully"], "success")
                            }).catch(error => {
                                this.$refs.status.showAlert(error.response.data.errors, "warning")
                            });

						}
					}
				}
			},
            concatUserUriAndId: function (userUri,id) {
			    return userUri+"/"+id
            },
			updatePageChange (page) {
				if (!this.isSort && !this.isFilter) {
					axios.get(this.userUri + "?page=" + page).then(usersResult => {
						this.dataTable = usersResult.data.data.result.data;
						this.dataTable["total"] = usersResult.data.data.total;
						this.dataTable["currentPage"] = page;
					});
				}
				else{
					this.usersSearchApi(page);
				}
			},
			usersSort(sortObj){
				if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
					this.isSort = true;
					this.sortBy = sortObj.sortBy;
					this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
					this.usersSearchApi(1);
				}
			},
			userFilter(keyword){
				this.isFilter = true;
				this.keyword = keyword;
				this.sortBy = "";
				this.usersSearchApi(1);
			},
			usersSearchApi (page){
				axios.get(this.queryUrlBuilder(this.userSearch, {page : page, order : this.sortDesc, keyword : this.keyword, sortBy: this.sortBy})).then(result => {
					this.dataTable  = result.data.data.result.data;
					this.dataTable["total"]  = result.data.data.total;
					this.dataTable["currentPage"] = page;
				}).catch(error => {
					this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
				});
			},
			clearFilter(){
				this.isFilter = false;
				this.isSort = false;
				this.sortBy = "";
				this.keyword = "";
        		this.initialLoad()
			},
			resetPassword(email){
				axios.put(this.resetPassUri, {email: email}).then(result => {
					this.$refs.status.showAlert(["Reset password successful"], "success")
				}).catch(error => {
					this.$refs.status.showAlert(error.response.data.errors, "warning")

				});
			}
		},
		data() {
			return {
				dataTable:[],
				waitingIndicator: false,
				waitingText: "Loading...",
				dataColumns:
					[
						{key: "name", label: "Name", visible: true, sortable: true, sortDirection: "desc",editable: true,"type":"TEXT" },
						{key: "email", label: "Email", visible: true, sortable: true,editable: true,"type":"TEXT" },
						{key: "password", label: "Password", visible: false, sortable: false,editable: true,"type":"PASSWORD" },
						{key: "company_id", label: "Company", sortable: true,"visible" : false, "editable" : true, "type":"DROPDOWN",
							"options" : [
							]
						},
						{key: "company_name", label: "Company", sortable: true,"visible" : true, "editable" : false, "type":"TEXT"},
						{key: "role", label: "Level", sortable: true,"visible" : true, "editable" : true, "type":"DROPDOWN",
							"options" :[
								{ value: "client" , text: "Client"},
								{ value: "admin" , text: "Client Admin"},
								{ value: "neubus" , text: "Neubus"},
								{ value: "it" , text: "IT"}
							]
						},
						{key: "actions", label: "Actions", visible: true, sortable: false},
						{key: "reset_password", label: "Reset Password", visible: true, sortable: false}
					],
				isSort: false,
				sortBy: "",
				sortDesc: false,
				isFilter: false,
				keyword: "",

			};
		}
	}
</script>
