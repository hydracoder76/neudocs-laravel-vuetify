<template>
    <div class="col-12">
        <div class="panel-heading" dusk="panel-header">Request Review</div>
        <slot></slot>
        <div id="requestsDiv" v-if="showRequest">
            <span v-show="waitingIndicator === false">
            <div class="neu-row">
                <div class="col-sm p-0">
                    <div class="requestDiv">
                        <neu-table title="Requests to Review"
                                   :data-neu-table="results"
                                   ref="status"
                                   :columns="columns"
                                   @page-change="pageChange"
                                   class="vm-margin"
                                   :create-new="false"
                                   :display-header="false"
                                   :no-local-sorting="true"
                                   :download-url="downloadUrl"
                                   @neu-sort-changed="requestsSort"
                                   @neu-accept-review="acceptReview"
                                   @neu-reject-review="rejectReview"
                        >
                        </neu-table>
                    </div>
                </div>
            </div>
                </span>
        </div>

        <b-modal v-model="reviewModal" dusk="modal-review-confirm" title="Confirm" @ok="reviewRequest">
            <p class="my-4">{{ message }}</p>
        </b-modal>
        <b-modal v-model="reviewModalError" dusk="modal-review-error" title="Error">
            <p class="my-4">Rejected request needs a comment</p>
        </b-modal>
        <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
                             shadow-icon="cog"></neu-shadow-backdrop>
    </div>
</template>
<style>
    .requestDiv {
        margin-top: 50px;
    }
</style>
<script>
    import bContainer from "bootstrap-vue/es/components/layout/container";
    import NeuButton from "../util/NeuButton";
    import NeuTable from "../util/NeuTable";
    import neuInputUtils from "../mixins/neu-input-utils";
    import neuUtils from "../mixins/neu-utils";
    import bModal from "bootstrap-vue/es/components/modal/modal";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";
    import axios from "axios";

    export default {
        data() {
            return {
                results: [],
                searchQuery: {},
                filterNewRequest: false,
                filterShow: false,
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
                    {
                        key: "requested_on",
                        label: "Requested On",
                        visible: true,
                        editable: false,
                        "type": "TEXT",
                        sortable: true
                    },
                    {
                        key: "fulfilled_on",
                        label: "Fulfilled On",
                        visible: true,
                        editable: false,
                        "type": "TEXT",
                        sortable: true
                    },
                    {
                        key: "download",
                        label: "Download",
                        visible: true,
                        editable: false,
                        "type": "TEXT",
                        sortable: false
                    },
                    {key: "comment", label: "Comment", visible: true, editable: false},
                    {key: "review", label: "Actions", visible: true, editable: false},
                    {key: "external_comment", label: "External Comments", visible: true, editable: false}
                ],
                showRequest: false,
                projectSelected: {},
                newRequestUrlComputed: "",
                sortBy: "requested_on",
                sortDesc: "asc",
                message: "",
                accept: true,
                reviewIndex: 0,
                reviewModal: false,
                reviewModalError: false,
                waitingIndicator: null,
                waitingText: "Loading..."
            };
        },
        mixins: [neuInputUtils, neuUtils],
        created() {
            this.$on("neu-item-selected", value => {
                if (value !== null) {
                    this.itemSelected(value);
                    this.$root.$emit("neu-item-selected", value);
                }
            });
        },
        methods: {
            itemSelected(projectObj) {
                this.projectSelected = projectObj;
                this.showRequest = true;
                this.sortBy = "requested_on";
                this.sortDesc = "asc";
                this.resultsSearch(1, {});
            },
            refreshResultsFunc(results) {
                this.searchQuery = results["indexes"];
                this.sortBy = "requested_on";
                this.sortDesc = "asc";
                this.resultsSearch(1, this.searchQuery);
            },
            showFilter() {
                this.filterShow = !this.filterShow;
            },
            resultsSearch(page, query) {
                let thisUrl = this.queryUrlBuilder(this.filterUrl, {
                    page: page,
                    indexes: JSON.stringify(query),
                    projectID: this.projectSelected.value,
                    getIndexes: false,
                    sortBy: this.sortBy,
                    order: this.sortDesc,
                    review: true
                });
                this.waitingIndicator = true;
                axios.get(thisUrl).then(result => {
                    this.waitingIndicator = false;
                    this.results = result.data.data.results.requestResults;
                    this.results["total"] = result.data.data.results.total;
                    this.results["currentPage"] = page;
                }).catch(error => {
                    this.waitingIndicator = false;
                    this.$refs.status.showAlert([error.response.data.errors], 'warning');
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
            },
            acceptReview(index) {
                this.accept = true;
                this.reviewIndex = index;
                this.message = "Are you sure this request is ready to be downloaded by the requester?";
                this.reviewModal = true;
            },
            rejectReview(index) {
                this.accept = false;
                this.reviewIndex = index;
                this.message = "Are you sure you want to reject this request?";
                this.reviewModal = true;
            },
            reviewRequest() {
                let comment = this.results[this.reviewIndex].commentText;
                if (typeof (comment) === "undefined") {
                    comment = "";
                }
                comment = comment.trim();
                if (!this.accept && comment === "") {
                    this.message = "Rejected request needs comment to be filled in";
                    this.reviewModalError = true;
                    return false;
                }
                axios.post(this.reviewUrl, {
                    requestId: this.results[this.reviewIndex].request_id,
                    comment: comment,
                    accept: this.accept
                }).then(result => {
                    this.reviewModal = false;
                    let changePage = this.$refs.currentPage;
                    if (this.results.length <= 1 && changePage > 1) {
                        changePage -= 1;
                    }
                    this.pageChange(changePage);
                    const submitText = this.accept ? "approval" : "rejection";
                    this.$refs.status.showAlert(["Review " + submitText + " submitted"], 'success');
                }).catch(error => {
                    this.$refs.status.showAlert([error.response.data.errors], 'warning');
                });
            }
        },
        props: {
            filterUrl: {
                type: String,
                required: true
            },
            reviewUrl: {
                type: String,
                required: true
            },
            downloadUrl: {
                type: String,
                required: true
            }
        },
        components: {
            bContainer, NeuButton, NeuTable, bModal, NeuShadowBackdrop
        }
    };
</script>
