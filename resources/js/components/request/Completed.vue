<template>
    <div class="col-12">
        <h2 class="panel-heading">Completed Requests</h2>
        <span>A list of completed requests and their part information.</span>
        <slot></slot>
        <span v-show="waitingIndicator === false">
		<div class="neu-row">
            <div class="col-sm p-0">
				<neu-table
                        title=""
                        type="edit"
                        :data-neu-table="dataTable"
                        :columns="dataColumns"
                        class="vm-margin"
                        :create-new="false"
                        :download-url="downloadUri"
                        :no-local-sorting="true"
                        :no-local-filtering="true"
                        @neu-sort-changed="completedSort"
                        @page-change="changePage"
                        @filter-ok="filterPage"
                        @clear-filter-ok="clearFilter">
				</neu-table>
			</div>
		</div>
			</span>
        <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
                             shadow-icon="cog"></neu-shadow-backdrop>
    </div>
</template>

<script>
    import NeuTable from "../util/NeuTable";
    import NavBar from "../NavBar";
    import NeuForm from "../forms/NeuForm";
    import Indexes from "./Indexes";
    import neuInputUtils from "../mixins/neu-input-utils";
    import axios from "axios";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";

    export default {
        components: {
            NeuTable, NavBar, NeuForm, NeuShadowBackdrop
        },
        created() {
            this.$on("neu-item-selected", value => {
                if (value != null) {
                    this.itemSelected(value);
                    this.$root.$emit("neu-item-selected", value);
                } else {
                    this.projectSelected = null;
                }
            });
        },
        methods: {
            itemSelected(projectObj) {
                this.projectSelected = projectObj;
                this.getResults(this.listUrl, 1);
            },
            getResults(url, page) {
                this.waitingIndicator = true;
                axios.get(url, {
                    "params": {
                        "project": this.projectSelected.value,
                        "page": page,
                        "sortBy": this.sortBy,
                        "order": this.sortDesc,
                        "filter_by": this.filterText
                    }
                }).then(result => {
                    this.waitingIndicator = false;
                    this.dataTable = result.data.data.results;
                    this.dataTable["total"] = result.data.data.length;
                    this.dataTable["currentPage"] = page;
                }).catch(error => {
                    this.waitingIndicator = false;
                    if (error.response.status === 404) {
                        this.dataTable = [];
                    }
                    this.errors = error.response.data.errors;
                });
            },
            completedSort(sortObj) {
                if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
                    this.isSort = true;
                    this.sortBy = sortObj.sortBy;
                    this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
                    this.getResults(this.filterUrl, 1);
                }
            },
            filterPage(filter) {
                this.filterText = filter;
                this.sortBy = "";
                this.getResults(this.filterUrl, 1);
            },
            clearFilter() {
                this.filterText = "";
                this.sortBy = "";
                this.isSort = false;
                this.getResults(this.listUrl, 1);
            },
            changePage(page) {
                if (this.filterText === "" && !this.isSort)
                    this.getResults(this.listUrl, page);
                else
                    this.getResults(this.filterUrl, page);
            }
        },
        data() {
            return {
                filterText: "",
                dataTable: [],
                dataColumns:
                    [
                        {
                            key: "request",
                            label: "Request Name",
                            visible: true,
                            sortable: true,
                            editable: false,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {key: "index", label: "Index", visible: true, sortable: false, editable: false, "type": "TEXT"},
                        {
                            key: "location",
                            label: "Location",
                            visible: true,
                            sortable: true,
                            editable: false,
                            "type": "TEXT"
                        },
                        {key: "box", label: "Box Name", visible: true, sortable: true, editable: false, "type": "TEXT"},
                        {
                            key: "part",
                            label: "Part Name",
                            sortable: true,
                            "visible": true,
                            "editable": false,
                            "type": "TEXT"
                        },
                        {
                            key: "requested_at",
                            label: "Requested At",
                            sortable: true,
                            visible: true,
                            "editable": false,
                            sortDirection: "desc",
                            "type": "TEXT"
                        },
                        {
                            key: "completed_at",
                            label: "Completed At",
                            sortable: true,
                            visible: true,
                            editable: false,
                            type: "TEXT"
                        },
                        {
                            key: "completed_by",
                            label: "Completed By",
                            sortable: true,
                            visible: true,
                            editable: false,
                            type: "TEXT"
                        },
                        {
                            key: "download",
                            label: "Download",
                            sortable: false,
                            visible: true,
                            editable: false,
                            type: "TEXT"
                        },
                        {
                            key: "external_comment",
                            label: "External Comments",
                            sortable: false,
                            visible: true,
                            editable: false,
                            type: "TEXT"
                        }
                    ],
                projectSelected: null,
                isSort: false,
                sortBy: "",
                sortDesc: false,
                waitingIndicator: null,
                waitingText: "Loading..."
            };
        },
        props: {
            listUrl: {
                type: String,
                required: true
            },
            filterUrl: {
                type: String,
                required: true
            },
            downloadUri: {
                type: String,
                required: true
            }
        },
        mixins: [neuInputUtils]
    };
</script>
