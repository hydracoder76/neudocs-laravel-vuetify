<template>
    <neu-form>
        <neu-table title="Manage Project"
                   tabname="Project"
                   type="edit"
                   ref="status"
                   :data-neu-table="dataTable"
                   :columns="dataColumns"
                   :project-load-uri="projectLoad"
                   :project-uri="projectUri"
                   :user-load-uri="userLoad"
                   :company-load-uri="companyLoad"
                   :no-local-sorting="true"
                   :no-local-filtering="true"
                   @add-ok="add"
                   @edit-ok="edit"
                   @delete-ok="deletefn"
                   @page-change="updatePageChange"
                   @neu-sort-changed="projectsSort"
                   @filter-ok="projectFilter"
                   @clear-filter-ok="clearFilter"
                   @input-select="inputSelect"
                   class="vm-margin"
        >
        </neu-table>
    </neu-form>
</template>

<script>
    import NeuTable from "./util/NeuTable"
    import NavBar from "./NavBar"
    import axios from "axios"
    import NeuForm from "./forms/NeuForm"
	import neuInputUtils from "./mixins/neu-input-utils";

    export default {

        props: {
            projectLoad: {
                type: String,
                required: true
            },
            projectUri: {
                type: String,
                required: true
            },
            userLoad: {
                type: String,
                required: true
            },
            companyLoad: {
                type: String,
                required: true
            },
            projectSort: {
            	type: String,
                required: true
            },
            mediaTypeLoad: {
                type: String,
                required: true
            }
        },
		mixins: [neuInputUtils],
        components: {
            NeuTable, NavBar, NeuForm
        },
        mounted() {
        	axios.get(this.companyLoad).then(companiesResult => {
				this.populateDataColumnOptions(companiesResult.data.data[0],"company_id","company_name");
				this.setProjects();
            });
            this.setMediaTypes();
        },
        methods: {
            populateDataColumnOptions(data,key,attributeName){
            	let j = 0
                for (let i in data) {
                    this.dataColumns.forEach(function(el2){
                        if(el2.key === key) {
							if (j === 0){
							    el2.options = []
								j++
						    }
                            if (attributeName === "company_name"){
                                el2.options.push({
                                    value: data[i].id,
                                    text: (attributeName === null ) ? data[i].name : data[i].company_name
                                })
                            }else if(attributeName === "type"){
                                el2.checkboxOptions.push({
                                    value: data[i].id,
                                    text: data[i].type
                                })
                            }

                        }
                    })
                }
            },

            userPartUri(companyId) {
				return this.userLoad + "/" + companyId
			},
			inputSelect (companyId) {
            	if (companyId !== "" ) {
					axios.get(this.userPartUri(companyId)).then(usersResult => {
						if(usersResult.data.data.length) {
							this.populateDataColumnOptions(usersResult.data.data[0], "project_owner_id");
						}
					}).catch(error => {
						this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
					});
				}
			},
			updatePageChange (page) {
            	if (!this.isSort && !this.isFilter) {
					axios.get(this.queryUrlBuilder(this.projectUri, {page: page})).then(usersResult => {
						this.dataTable = usersResult.data.data.result;
						this.dataTable["total"] = usersResult.data.data.total;
						this.dataTable["currentPage"] = page;
					}).catch(error => {
						this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
					});
				}
            	else{
            		this.projectsSortApi(page);
                }
			},
            projectsSort(sortObj){
				if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
					this.isSort = true;
					this.sortBy = sortObj.sortBy;
					this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
					this.projectsSortApi(1);
				}
            },
            projectFilter(keyword){
            	this.isFilter = true;
            	this.keyword = keyword;
				this.sortBy = "";
            	this.projectsSortApi(1);
            },
            clearFilter(){
            	this.isFilter = false;
            	this.isSort = false;
            	this.sortBy = "";
            	this.keyword = "";
            	this.updatePageChange(1);
            },
            projectsSortApi(page) {
                axios.get(this.queryUrlBuilder(this.projectSort, {
                    page: page,
                    order: this.sortDesc,
                    keyword: this.keyword,
                    sortBy: this.sortBy
                })).then(usersResult => {
                    this.dataTable = usersResult.data.data.result;
                    this.dataTable["total"] = usersResult.data.data.total;
                    this.dataTable["currentPage"] = page;
                }).catch(error => {
                    this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
                });
            },
            add(data)  {
                axios.post(this.projectUri,data).then(result =>{
                    this.dataTable.push(result.data.data.result);
                    this.$refs.status.showAlert([result.data.message], "success");
                }).catch(error => {
                    this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
                });
            },
            edit (data) {
                axios.put(this.concatProjectUriAndId(this.projectUri,data.id),data).then(result =>{
                    this.dataTable.forEach(function(elem){
                        if(elem.id === data.id) {
                            for(const i in data){
                                elem[i] = data[i];
                            }                                
                        }
                    })
                    this.setProjects();
                    this.$refs.status.showAlert([result.data.message], "success");
                }).catch(error => {
                    this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
                });
            },
            deletefn (data) {
				for (let i = 0; i < this.dataTable.length; i++) {
					for (let j = 0; j < data.length; j++) {
						if (this.dataTable[i].id === data[j].id) {
							axios.delete(this.concatProjectUriAndId(this.projectUri,data[j].id)).then(({data})=>{
								this.dataTable.splice(i, 1)
								this.$refs.status.showAlert([data.message], "success")
							}).catch(error => {
								this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
							});

						}
					}
				}
            },
            concatProjectUriAndId (projectUri,id) {
                return projectUri+"/"+id
            },
            setMediaTypes(){
                axios.get(this.mediaTypeLoad).then(mediaTypeResult => {
                    this.populateDataColumnOptions(mediaTypeResult.data.data[0],"media_type_id","type");
                });
            },
            setProjects(){
                axios.get(this.projectLoad).then(projectsResult => {
                    this.dataTable  = projectsResult.data.data.result;
                    this.dataTable["total"] = projectsResult.data.data.total;
                    this.dataTable["currentPage"] = 1;
                });
            }
        },
        data() {
            return {
                dataTable: [],
                dataColumns: [
                    {
                        key: "project_name",
                        label: "Project Name",
                        sortable: true,
                        sortDirection: "desc",
                        "visible": true,
                        "editable": true,
                        "type": "TEXT"
                    },
                    {
                        key: "project_description",
                        label: "Description",
                        sortable: true,
                        "visible": true,
                        "editable": true,
                        "type": "TEXT"
                    },
                    {
                        key: "company_id",
                        label: "Company",
                        sortable: false,
                        "visible": false,
                        "editable": true,
                        "type": "DROPDOWN",
                        "options": []
                    },
                    {
                        key: "media_type_id",
                        label: "Media Types",
                        sortable: false,
                        "visible": false,
                        "editable": true,
                        "type": "CHECKBOX",
                        "checkboxOptions": []
                    },
                    {
                        key: "company_name",
                        label: "Company",
                        sortable: true,
                        "visible": true,
                        "editable": false,
                        "type": "TEXT"
                    },
                    {
                        key: "type",
                        label: "Media Type",
                        sortable: true,
                        "visible": true,
                        "editable": false,
                        "type": "TEXT"
                    },
                    {key: "actions", label: "Actions", visible: true}
                ],
                isSort: false,
                sortBy: "",
                sortDesc: false,
                isFilter: false,
                keyword: ""
            }
        }
    }
</script>

