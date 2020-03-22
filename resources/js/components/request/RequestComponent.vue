<template>
    <div class="col-12">
        <div class="panel-heading" dusk="panel-header">Request</div>
        <div v-if="!showRequest">
            <slot></slot>
        </div>

        <div id="requestsDiv" v-if="showRequest">
            <div class="neu-row">
                <div class="offset-3 col-md-6">
                    <slot></slot>
                </div>
            </div>
            <span v-show="waitingIndicator === false">
            <span class="neu-row pt-3">
                    <div id="requestSearchDiv" class="col-md-3 requestDiv">
                        <div class="neu-requestDiv-label">
                            <span v-if="!filterShow" class="fas fa-chevron-right"></span>
                            <span v-else class="fas fa-chevron-down"></span>
                            <a href="#" @click="showFilter" dusk="request-filter">Search existing requests</a>
                        </div>
                        <indexes v-if="filterShow"
                                 :indexes-json="indexesJson"
                                 :filter-url="filterUrl"
                                 :filter-event="'refresh-results'"
                                 :auto-on="false"
                                 @refresh-results="refreshResultsFunc"></indexes>
                    </div>
                    <div class="requestDiv col-md-9">
                        <div class="neu-table-action-row text-right">
                            <neu-button fa-icon="plus" :btn-href="newRequestUrlComputed"
                                        btn-text="New Request"></neu-button>
                        </div>
                        <neu-table title="Requests"
                                   :data-neu-table="results"
                                   ref="status"
                                   :columns="columns"
                                   @page-change="pageChange"
                                   class="vm-margin p-0"
                                   :create-new="false"
                                   :display-header="false"
                                   :download-url="downloadUrl"
                                   :no-local-sorting="true"
                                   @neu-sort-changed="requestsSort"
                        >
                        </neu-table>
                    </div>
            </span>
            </span>
        </div>

    </div>
</template>
<style scoped>

</style>
<script>
    import bContainer from "bootstrap-vue/es/components/layout/container";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";
    import Indexes from "./Indexes";
    import NewRequest from "./NewRequest";
    import NeuButton from "../util/NeuButton";
    import NeuTable from "../util/NeuTable";
    import neuInputUtils from "../mixins/neu-input-utils";
    import neuUtils from "../mixins/neu-utils";
    import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
    import axios from "axios";
    // add needed icons here
    import {faSearch} from "@fortawesome/free-solid-svg-icons";

    export default {
        data() {
            return {
                results: [],
                searchQuery: {},
                filterNewRequest: false,
                columns: [
                    {
                        key: "request_number",
                        label: "Request Number",
                        visible: true,
                        editable: false,
                        type: "TEXT",
                        sortable: true
                    },
                    {
                        key: "request_indexes",
                        label: "Request Part Information",
                        visible: true,
                        editable: false,
                        "type": "TEXT",
                        sortable: false
                    },
                    {key: "status", label: "Status", visible: true, editable: false, type: "TEXT", sortable: true},
                    {
                        key: "requested_on",
                        label: "Requested On",
                        visible: true,
                        editable: false,
                        type: "TEXT",
                        sortable: true
                    },
                    {
                        key: "fulfilled_on",
                        label: "Fulfilled On",
                        visible: true,
                        editable: false,
                        type: "TEXT",
                        sortable: true
                    },
                    {
                        key: "download",
                        label: "Download",
                        visible: true,
                        editable: false,
                        type: "TEXT",
                        sortable: false
                    },
                    {
                        key: "external_comment",
                        label: "External Comments",
                        visible: true,
                        editable: false,
                        type: "TEXT",
                        sortable: false
                    },

                    {
                        key: "media_type_id",
                        label: "Media Types",
                        sortable: false,
                        "visible" : true,
                        "editable" : true,
                        "type":"CHECKBOX",
                        "checkboxOptions" : [

                        ]
                    }
                ],
                showRequest: false,
                projectSelected: {},
                newRequestUrlComputed: "",
                indexes: {},
                indexesJson: "{}",
                sortBy: "",
                sortDesc: false,
                filterShow: false,
                waitingIndicator: null,
                waitingText: "Loading..."
            };
        },
        mixins:[neuInputUtils, neuUtils],
        created() {
            this.$on("neu-item-selected", value => {
                if (value !== null) {
                    this.itemSelected(value);
                    this.$root.$emit("neu-item-selected", value);
                }
            });
        },
        methods: {
            showFilter() {
                this.filterShow = !this.filterShow;
            },
            getFaIconFromString(iconName) {
                switch (iconName) {
                case "search":
                    return faSearch;
                default:
                    return "";
                }
            },
            itemSelected(projectObj) {
                this.projectSelected = projectObj;
                this.newRequestUrlComputed = this.newRequestUrl + "/" + this.projectSelected.label;
                this.showRequest = true;
                this.searchQuery = [];
                this.sortBy = "";
                this.resultsSearch(1, {}, true);
            },
            refreshResultsFunc(results) {
                this.searchQuery = results.indexes;
                this.sortBy = "";
                this.resultsSearch(1, this.searchQuery);
            },
            resultsSearch(page, query, getIndexes) {
                const thisUrl = this.queryUrlBuilder(this.filterUrl, {
                    page: page,
                    indexes: JSON.stringify(query),
                    projectID: this.projectSelected.value,
                    getIndexes: getIndexes,
                    sortBy: this.sortBy,
                    order: this.sortDesc,
                    review: false
                });
                this.waitingIndicator = true;
                axios.get(thisUrl).then(result => {
                    this.results = result.data.data.results.requestResults;
                    this.results.total = result.data.data.results.total;
                    this.results.currentPage = page;
                    if (getIndexes) {
                        this.indexes = result.data.data.results.indexes;
                        this.indexesJson = JSON.stringify(result.data.data.results.indexes);
                    }
                    this.waitingIndicator = false;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.waitingIndicator = false;
                });
            },
            pageChange(page) {
                this.resultsSearch(page, this.searchQuery, false);
            },
            requestsSort(sortObj) {
                if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
                    this.sortBy = sortObj.sortBy;
                    this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
                    this.pageChange(1);
                }
            }
        },
        props: {
            username: {
                type: String,
                required: true
            },
            filterUrl: {
                type: String,
                required: true
            },
            downloadUrl: {
                type: String,
                required: true
            },
            newRequestUrl: {
                type: String,
                required: true
            }
        },
        components: {
            Indexes, bContainer, NewRequest, NeuButton, NeuTable, FontAwesomeIcon, NeuShadowBackdrop
        }
    };
</script>
