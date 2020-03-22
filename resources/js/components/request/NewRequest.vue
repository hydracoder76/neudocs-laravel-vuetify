<template>
    <div class="col-12 clearfix">
        <div class="panel-heading" dusk="panel-header">Add New Request</div>

        <div v-show="firstScreen">
            <indexes
                    :indexes-json="indexesJson"
                    :filter-url="filterUrl"
                    :project-id="projectId"
                    :filter-click="requestSearch"
                    :filter-event="'request-search-evt'"
                    :auto-on="true"
                    :auto-url="autoUrl"
                    col-class="col-3"
                    @request-search-evt="finishSearchFunc"></indexes>
            <div v-show="searched" class="">
                <div class="neu-table-action-row d-flex justify-content-between">
                    <div><!-- line up the button --></div>
                    <neu-button btn-size="md" btn-text="Add to Request" @neu-btn-click="goToReview"
                                fa-icon="plus" btn-dusk="request-review"></neu-button>
                </div>
                <neu-table
                        title="Select Parts to Include in Request"
                        :data-neu-table="parts"
                        @page-change="pageChange"
                        class="vm-margin p-0"
                        :create-new="false"
                        :display-header="false"
                        :columns="columns"
                        ref="status"
                        :per-page="10"
                        :no-local-sorting="true"
                        :empty-text="emptyText"
                        @neu-sort-changed="partsSort"
                        @part-check="partCheck"></neu-table>
            </div>
        </div>
        <div v-show="!firstScreen">
            <neu-table
                    title="Review Selected Parts"
                    :data-neu-table="selectedParts"
                    class="vm-margin p-0"
                    :create-new="false"
                    :custom-action="true"
                    :display-header="false"
                    :columns="selectedColumns"
                    ref="selected"
                    :all-paginate="true"
                    @neu-delete-part="deletePart"
            >
                <template slot="custom-action">
                    <div class="neu-table-action-row d-flex justify-content-between">
                        <neu-button btn-size="md" btn-text="Add More Parts to this Request"
                                    @neu-btn-click="goToSearch" btn-dusk="request-select-back"></neu-button>
                        <neu-button fa-icon="plus" btn-size="md" btn-text="Create New Request"
                                    @neu-btn-click="newRequest" btn-dusk="request-create"></neu-button>
                    </div>
                </template>
            </neu-table>

            <label for="external-comments">External Comments:</label>
            <b-form-textarea rows="6" class="col-md-5" v-model="externalComments" id="external-comments"
                             name="external-comments" aria-label="External Comments"></b-form-textarea>

        </div>
        <div class="text-left pt-4">
            <neu-button fa-icon="back" btn-type="action" btn-size="md" btn-text="Back to Requests"
                        @neu-btn-click="requestBack" btn-dusk="request-back"></neu-button>
        </div>
        <b-modal dusk="modalRequest" @ok="requestGoBack" ref="requestModal" :ok-only="okOnly">
            <div slot="modal-header"></div>
            <p class="my-4">{{ modalMessage }}</p>
        </b-modal>
    </div>
</template>

<script>
    import Indexes from "./Indexes";
    import bButton from "bootstrap-vue/es/components/button/button";
    import bModal from "bootstrap-vue/es/components/modal/modal";
    import bTable from "bootstrap-vue/es/components/table/table";
    import bPagination from "bootstrap-vue/es/components/pagination/pagination";
    import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
    import bFormTextarea from "bootstrap-vue/es/components/form-textarea/form-textarea";
    import NeuButton from "../util/NeuButton";
    import neuUtilEvent from "../mixins/neu-input-utils";
    import NeuTable from "../util/NeuTable";
    import axios from "axios";

    export default {
        data() {
            return {
                parts: [],
                requestSearch: false,
                searched: false,
                selectAll: false,
                tableValues: [],
                checkedItems: [],
                totalResults: 0,
                searchQuery: "",
                columns: [],
                indexes: [],
                firstScreen: true,
                selectedColumns: [],
                selectedParts: [],
                modalMessage: "",
                okOnly: false,
                selectStore: {},
                sortBy: "",
                sortDesc: false,
                addtoRequestDisabled: true,
                emptyText: "There are no records to show",
                externalComments: ""
            };
        },
        mounted() {
            this.columns = [{
                                key: "part_name",
                                label: "Part Name",
                                visible: true,
                                editable: false,
                                "type": "TEXT",
                                sortable: true
                            },
                            {key: "box_name", label: "Box Name", visible: true, editable: false, "type": "TEXT", sortable: true}];
            this.indexes = JSON.parse(this.indexesJson);
            for (const index in this.indexes) {
                this.columns.push({
                    key: this.indexes[index].name,
                    label: this.indexes[index].label,
                    visible: true,
                    editable: false,
                    "type": "TEXT",
                    sortable: true
                });
            }
            this.selectedColumns = JSON.parse(JSON.stringify(this.columns));
            this.columns.unshift({
                key: "selectPart",
                label: "Select",
                visible: true,
                editable: false,
                "type": "CHECKBOX",
                "slot": "false",
                sortable: false
            });
            this.selectedColumns.push({
                key: "file_actions_upload",
                label: "Delete from selection",
                visible: true,
                editable: true,
                "type": "TEXT",
                "slot": "false"
            });
            for (const selectedColumn in this.selectedColumns) {
                Vue.set(this.selectedColumns[selectedColumn], "sortable", false);
            }
        },
        mixins: [neuUtilEvent],
        methods: {
            finishSearchFunc(returned) {
                this.requestSearch = false;
                this.searchQuery = returned.indexes;
                this.sortBy = "";
                this.selectStore = {};
                this.resultsSearch(1, this.searchQuery);
                this.searched = true;
            },
            goToReview() {
                this.getSelected();
                this.sortSelected();
                this.selectedParts.total = this.selectedParts.length;
                this.selectedParts.currentPage = 1;
                this.selectStore = {};
                this.firstScreen = false;
                this.parts = [];
            },
            goToSearch() {
                this.firstScreen = true;
            },
            partCheck(value, part) {
                if (value) {
                    this.selectStore[part.part_id] = part;
                } else if (this.selectStore.hasOwnProperty(part.part_id)) {
                    delete this.selectStore[part.part_id];
                }
            },
            newRequest() {
                const parts = this.getReviewed();
                if (parts.length === 0) {
                    this.$refs.selected.showAlert(["Need at least one part"], "warning");
                    return;
                }
                axios.post(this.newRequestUrl, {
                    "partIDs": parts,
                    "externalComment": this.externalComments,
                    "projectID": this.projectId
                }).then(result => {
                    this.modalMessage = result.data.data.message;
                    this.okOnly = true;
                    this.$refs.requestModal.show();
                }).catch(error => {
                    this.$refs.selected.showAlert([error.message], "warning");
                });
            },
            resultsSearch(page, query) {
                this.parts = [];
                this.emptyText = "Searching...";
                const thisUrl = this.queryUrlBuilder(this.filterUrl, {
                    page: page, indexes: JSON.stringify(query),
                    projectID: this.projectId, sortBy: this.sortBy, order: this.sortDesc, review: false
                });
                axios.get(thisUrl).then(result => {
                    this.parts = result.data.data.results;
                    this.parts.total = result.data.data.total;
                    this.parts.currentPage = page;
                    for (const part in this.parts) {
                        if (this.selectStore.hasOwnProperty(this.parts[part].part_id)) {
                            this.parts[part].selectPart = true;
                        }
                    }
                    this.emptyText = "There are no records to show";
                }).catch(error => {
                    this.$refs.status.showAlert([error.message], "warning");
                });
            },
            partsSort(sortObj) {
                // this doesn't take in to account situations where sorting should not occur
                // so the emitted event will have a null value for sortby, thus causing the issue in NSN-1252
                // since null is a possible value in sort situations, we need to account for it
                // thus this big is a result of missing that check
                if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
                    this.sortBy = sortObj.sortBy;
                    this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
                    this.pageChange(1);
                }
            },
            pageChange(page) {
                this.resultsSearch(page, this.searchQuery);
            },
            getSelected() {
                for (const store in this.selectStore) {
                    const curPart = this.selectStore[store];
                    if (!this.checkIfSelected(curPart.part_id)) {
                        curPart.file_actions_upload = {
                            eventName: "neu-delete-part",
                            isActing: false, icon: "trash-alt", styles: "neu-delete-icon", rowId: curPart.part_id
                        };
                        this.selectedParts.push(curPart);
                    }
                }
            },
            checkIfSelected(partId) {
                for (const part in this.selectedParts) {
                    if (this.selectedParts[part].part_id === partId) {
                        return true;
                    }
                }
                return false;
            },
            sortSelected() {
                this.selectedParts.sort(function (a, b) {
                    const aBoxName = a.box_name,
                          bBoxName = b.box_name,
                          aPartName = a.part_name,
                          bPartName = b.part_name;
                    if (a.box_name === b.box_name) {
                        return (aPartName < bPartName) ? -1 : (aPartName > bPartName) ? 1 : 0;
                    }

                    return (aBoxName < bBoxName) ? -1 : 1;

                });
            },
            getReviewed() {
                const parts = [];
                for (let part = 0; part < this.selectedParts.length; part++) {
                    parts.push(this.selectedParts[part].part_id);
                }
                return parts;
            },
            deletePart(partId) {
                this.selectedParts.forEach((partInfo, index) => {
                    if (partInfo.part_id === partId) {
                        Vue.delete(this.selectedParts, index);
                        this.selectedParts.currentPage = Math.ceil(this.selectedParts.length / 25);
                        return;
                    }
                });
            },
            requestBack() {
                if (this.selectedParts.length > 0) {
                    this.modalMessage = "There are unstaged changes. Are you sure you want to go back to the Requests page?";
                    this.okOnly = false;
                    this.$refs.requestModal.show();
                } else {
                    this.requestGoBack();
                }
            },
            requestGoBack() {
                location.href = this.requestUrl;
            }
        },
        props: {
            indexesJson: {
                type: String,
                required: true
            },
            projectId: {
                type: String,
                required: true
            },
            filterUrl: {
                type: String,
                required: true
            },
            newRequestUrl: {
                type: String,
                required: true
            },
            requestUrl: {
                type: String,
                required: true
            },
            autoUrl: {
                type: String,
                required: true
            }
        },
        components: {
            bButton, bModal, Indexes, bTable, bPagination, bFormCheckbox, NeuButton, NeuTable, bFormTextarea
        }
    };
</script>
