<template>

    <div>

        <slot></slot>
        <span v-show="waitingIndicator === false">
        <neu-form dusk="neu-dataentry-form" :submission-uri="submitTo" v-if="showTable">
            <neu-table dusk="dataentry-neu-table" title="Manage Boxes"
                       tabname="Box"
                       type="edit"
                       :request-uri="requestUri"
                       :data-neu-table="dataTable"
                       :columns="dataColumns"
                       :create-new="false"
                       :username="username"
                       :no-local-sorting="true"
                       :no-local-filtering="true"
                       class="vm-margin"
                       ref="status"
                       @row-click-ok="rowClickedOk"
                       @page-change="updatePageChange"
                       @neu-sort-changed="boxSort"
                       @filter-ok="boxFilter"
                       @clear-filter-ok="clearFilter"
                       tabledesc="Click the box number to open the Box Details page to view/add parts">
                <template slot="add-box-button">
                    <b-button type="button" dusk="add-box-button" @click="showAddBox">Add Box</b-button>
                </template>


            </neu-table>
        </neu-form>
            <!-- Info Add Box modal -->
        <neu-form dusk="neu-dataentry-box-modal" :is-modal="true" neu-form-modal-id="neu-dataentry-box-modal"
                  @neu-submit="submitNewBox" modal-size="lg" :hide-modal-footer="true">
            <template slot="neu-form-modal-title">
                Add Box
            </template>
            <template slot="neu-modal-form-content">
                <b-alert dusk="statusCRUD" :show="dismissCountDown" variant="warning" dismissible fade
                         @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
                    <p v-for="(message, key) in errors">{{ message }}</p>
                </b-alert>
                <b-form-group v-for="(item) in editableColumns" horizontal :label-cols="2" :label="item.label"
                              :key="item.id">
                    <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
                        <input :dusk="item.key" v-model.trim="dataAddBox[item.key]" :type="item.type"
                               class="neu-input neu-input-lg" onpaste="return false;"/>
                    </template>
                    <template v-else-if="item.type === 'DROPDOWN'">
                        <b-form-select :dusk="item.key" :options="item.options" v-model="dataAddBox[item.key]"
                                       class="neu-input neu-input-lg"/>
                    </template>
                </b-form-group>
                <div slot="modal-footer">
                    <div class="float-left">
                        <neu-button btn-size="md" btn-type="cancel" btn-text="Cancel"
                                    dusk="neu-dataentry-cancel-box-btn"
                                    @neu-btn-click="cancelBox"></neu-button>
                    </div>
                    <div class="float-right">
                        <neu-button btn-size="md" btn-text="Add Part" dusk="neu-dataentry-add-part-btn"
                                    @neu-btn-click="showAddPart"></neu-button>
                        <neu-button btn-size="md" btn-text="Add" dusk="neu-dataentry-save-box-btn"
                                    @neu-btn-click="submitNewBox"></neu-button>
                    </div>
                </div>
            </template>

        </neu-form>


            <!-- Info Add Part modal -->
        <neu-form dusk="neu-dataentry-part-modal" :is-modal="true" neu-form-modal-id="neu-dataentry-part-modal"
                  @neu-submit="submitNewPart" modal-size="lg" :hide-modal-footer="true">
            <template slot="neu-form-modal-title">
                Add Part {{ dataAddPart["part_name"] }}
            </template>
            <template slot="neu-modal-form-content">

                <b-form-group v-for="(item) in editablePartColumns" horizontal :label="item.label" :key="item.id">
                    <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
                        <input :dusk="item.key" v-model.trim="dataAddPart[item.key]" :type="item.type"
                               class="neu-input neu-input-lg" onpaste="return false;"/>
                    </template>
                    <template v-else-if="item.type === 'DROPDOWN'">
                        <b-form-select :dusk="item.key" :options="item.options" v-model="dataAddPart[item.key]"
                                       class="neu-input neu-input-lg"
                                       @input="inputSelect(dataAddPart[item.key],item.key)"/>
                    </template>


                    <template v-else-if="item.type === 'CHECKBOX'">
                        <b-form-checkbox :disabled="option.value == 1"
                                         v-for="option in item.checkboxOptions"
                                         v-model="dataAddChecked"
                                         :key="option.value"
                                         :value="option.value"
                                         name="flavour-3a"
                        >
                            {{ option.text }}
                        </b-form-checkbox>
                    </template>
                </b-form-group>
                <input type="text" style="display:none;">
                <div slot="modal-footer">
                    <div class="float-left">
                        <neu-button btn-size="md" btn-text="Cancel" btn-type="cancel"
                                    dusk="neu-dataentry-cancel-index-btn"
                                    @neu-btn-click="cancelPart"></neu-button>
                    </div>
                    <div class="float-right">
                        <template v-if="!isLoading">
                        <neu-button btn-size="md" btn-type="action" btn-text="Add" dusk="neu-dataentry-save-index-btn"
                                    @neu-btn-click="submitNewPart"></neu-button>
                        </template>
                        <template v-else>
                            <neu-button btn-size="md" btn-type="action" btn-text="Loading..."
                                        dusk="neu-dataentry-save-index-btn"></neu-button>
                        </template>
                    </div>
                </div>
            </template>
        </neu-form>
            </span>
        <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
                             shadow-icon="cog"></neu-shadow-backdrop>
    </div>


</template>

<script>
    import bModal from "bootstrap-vue/es/components/modal/modal";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
    import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
    import bInputGroupAppend from "bootstrap-vue/es/components/input-group/input-group";
    import NeuTable from "../util/NeuTable";
    import NavBar from "../NavBar";
    import NeuForm from "../forms/NeuForm";
    import moment from "moment";
    import bButton from "bootstrap-vue/es/components/button/button";
    import bContainer from "bootstrap-vue/es/components/layout/container";
    import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
    import NeuButton from "../util/NeuButton";
    import axios from "axios";
    import neuInputUtils from "../mixins/neu-input-utils";
    import bAlert from "bootstrap-vue/es/components/alert/alert";
    import neuHandleProjectID from "../mixins/neu-handle-projectID";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";
    import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";

    export default {
        name: "EditableDataEntryTable",
        components: {
            NeuTable,
            NavBar,
            NeuForm,
            bButton,
            bContainer,
            bModal,
            bFormGroup,
            bFormInput,
            bInputGroup,
            bInputGroupAppend,
            bFormSelect,
            NeuButton,
            bAlert,
            NeuShadowBackdrop,
            bFormCheckbox
        },
        props: {
            username: {
                type: String,
                required: true,
                default: ""
            },
            requestUri: {
                type: String,
                required: true
            },
            boxSubmissionUri: {
                type: String,
                required: true
            },
            partSubmissionUri: {
                type: String,
                required: true
            },
            partIndexSubmissionUri: {
                type: String,
                required: true
            },
            submitTo: {
                type: String,
                required: false,
                default: false
            },
            boxTypeDefUri: {
                type: String,
                required: true
            },
            dataEntryBoxSubmissionUri: {
                type: String,
                required: true
            },
            navUri: {
                type: String,
                required: true
            },
            partindexSchemaUri: {
                type: String,
                required: true
            }
        },
        created() {
            this.initialLoad();
            this.dataTable.total = this.dataTable.length;
            this.dataTable.currentPage = 1;
            let i = 0;
            for (const key of this.dataTable) {
                var today = moment(new Date());
                var requested_at_date = moment(key.requested_at);

                var duration = moment.duration(today.diff(requested_at_date));
                var days = duration.asDays();

                if (days <= 10) {
                    this.dataTable[i].request_status = "green";
                } else if (days <= 14) {
                    this.dataTable[i].request_status = "yellow";
                } else {
                    this.dataTable[i].request_status = "red";
                }
                i++;
            }

            this.$on("neu-item-selected", value => {
                if (value !== null) {
                    this.projectIDSelected(value);
                    this.$root.$emit("neu-item-selected", value);
                }
            });
        },
        methods: {
            updatePageChange(page) {
                this.waitingIndicator = true;
                this.waitingText = "Loading...";
                axios.get(this.queryUrlBuilder(this.dataEntryBoxSubmissionUri, {
                    projectId: this.projectSelected.value,
                    page: page, keyword: this.keyword, sortBy: this.sortBy, order: this.sortDesc
                })).then(result => {
                    this.dataTable = result.data.data.result;
                    this.dataTable.total = result.data.data.total;
                    this.dataTable.currentPage = page;
                    this.clearAllDataForAddFunction();
                    this.waitingIndicator = false;
                }).then(result => {
                    this.errors = [];
                    this.waitingIndicator = false;
                }).catch(error => {
                    this.errors = [error];
                    this.dataTable = [];
                    this.clearAllDataForAddFunction();
                    this.waitingIndicator = false;
                });
            },
            initialLoad() {
                axios.get(this.boxTypeDefUri).then(result => {
                    Vue.set(this.dataBoxColumns[3], "options", result.data.data);
                }).then(result => {
                    this.errors = [];
                }).catch(error => {
                    this.errors = [error];
                    this.clearAllDataForAddFunction();
                });
            },
            boxSort(sortObj) {
                this.isSort = true;
                this.sortBy = sortObj.sortBy;
                this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
                this.updatePageChange(1);
            },
            boxFilter(keyword) {
                this.isFilter = true;
                this.keyword = keyword;
                this.sortBy = "";
                this.updatePageChange(1);
            },
            clearFilter() {
                this.isFilter = false;
                this.isSort = false;
                this.sortBy = "";
                this.keyword = "";
                this.updatePageChange(1);
            },
            assignDataResultToDataAdd(dataAdd, result) {
                dataAdd["id"] = result.data.data.results[0].id;
                dataAdd["created_at"] = result.data.data.results[0].created_at;
                dataAdd["created_by_name"] = result.data.data.results[0].created_by_name;
                dataAdd["updated_by_name"] = result.data.data.results[0].updated_by_name;
                dataAdd["part_count"] = result.data.data.results[0].part_count;
                dataAdd["deleted_at"] = result.data.data.results[0].deleted_at;
            },
            submitNewBox() {
                axios.post(this.boxSubmissionUri, {
                    box_status: "DATA_ENTRY",
                    box_type: this.dataAddBox["box_type"],
                    box_name: this.dataAddBox["box_name"],
                    box_location_code: this.dataAddBox["box_location_code"],
                    project_id: this.projectSelected.value,
                    rfid: this.dataAddBox["rfid"]
                }).then(result => {
                    this.assignDataResultToDataAdd(this.dataAddBox, result);
                    this.addBoxOk();
                    this.errors = [];
                }).catch(error => {
                    this.setError(error.response.data.errors);
                });
            },
            submitNewPart() {
                this.callbackNewPart(this.resultBoxTemp);
            },
            callbackNewPart(resultBox) {
                this.isLoading = true;
                Vue.set(this.dataAddPart, "box_id", resultBox.data.data.results[0].id);
                axios.post(this.partSubmissionUri, {
                    part_name: this.dataAddPart.part_name,
                    box_id: this.dataAddPart.box_id,
                    project_id: this.projectSelected.value,
                    media_type_id: this.dataAddChecked
                }).then(resultPart => {
                    this.callbackNewIndex(resultPart);
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.clearAddBoxModal();
                    this.isLoading = false;
                });
            },
            callbackNewIndex(response) {
                Vue.set(this.dataAddPart, "part_id", response.data.data.id);
                for (const index in this.editablePartColumns) {
                    if (!this.dataAddPart.hasOwnProperty(this.editablePartColumns[index].key)) {
                        Vue.set(this.dataAddPart, this.editablePartColumns[index].key, "");
                    }
                }
                const data = JSON.parse(JSON.stringify(this.dataAddPart));
                axios.post(this.partIndexSubmissionUri, {data}).then(() => {
                    this.addPartOk();
                }).catch(error => {
                    this.$refs.status.showAlert(error.response.data.errors, "warning");

                });
            },
            clearAddBoxModal() {
                this.clearAllDataForAddFunction();
            },
            clearAddPartModal() {
                this.clearAllDataForAddFunction();
            },
            showAddBox() {
                this.editableColumns = this.dataBoxColumns;
                this.$root.$emit("bv::show::modal", "neu-dataentry-box-modal");
            },
            concatUri(uri, Id) {
                return uri + "/" + Id;
            },
            initialSchemaPartLoad() {
                this.waitingIndicator = true;
                this.waitingText = "Loading...";
                axios.post(this.concatUri(this.partindexSchemaUri, this.projectSelected.value)).then(resultPart => {
                    this.callbackAfterInitialSchemaPartLoad(resultPart);
                    this.waitingIndicator = false;

                }).catch(error => {
                    this.waitingIndicator = false;
                    if (error.response.data.hasOwnProperty("errors")) {
                        this.setError(error.response.data.errors);
                    } else {
                        this.setError([error.response.data.message]);
                    }
                });
            },
            handleColumns(editableColumn) {
                const editableColumn2 = editableColumn.filter((item) => {
                    return item.editable && item;
                });
                return editableColumn2;
            },
            callbackAfterInitialSchemaPartLoad(response) {
                this.dataPartColumns = response.data.data.result[0];
                this.editablePartColumns = this.handleColumns(this.dataPartColumns);
                const l = 0;

                if (l === 0) {
                    /* eslint no-undef:off */
                    Vue.set(this.dataAddPart, "part_name", 1);
                } else {
                    Vue.set(this.dataAddPart, "part_name", l + 1);
                }
                this.$root.$emit("bv::hide::modal", "neu-dataentry-box-modal");
                this.$root.$emit("bv::show::modal", "neu-dataentry-part-modal");
            },
            showAddPart() {
                /* eslint camelcase:off */
                axios.post(this.boxSubmissionUri, {
                    box_status: "DATA_ENTRY",
                    box_type: this.dataAddBox["box_type"],
                    box_name: this.dataAddBox["box_name"],
                    box_location_code: this.dataAddBox["box_location_code"],
                    project_id: this.projectSelected.value,
                    rfid: this.dataAddBox["rfid"]
                }).then(resultBox => {
                    this.assignDataResultToDataAdd(this.dataAddBox, resultBox);
                    this.resultBoxTemp = resultBox;
                    this.initialSchemaPartLoad();
                }).catch(error => {
                    this.setError(error.response.data.errors);
                });
            },
            addBoxOk() {
                this.dataTable.push(this.dataAddBox);
                this.clearAddBoxModal();
                this.$root.$emit("bv::hide::modal", "neu-dataentry-box-modal");
            },
            cancelBox() {
                this.clearAddBoxModal();
                this.$root.$emit("bv::hide::modal", "neu-dataentry-box-modal");
            },
            addPartOk() {
                this.dataTable.push(this.dataAddBox);
                this.clearAddBoxModal();
                this.$root.$emit("bv::hide::modal", "neu-dataentry-part-modal");
                this.isLoading = false;
            },
            cancelPart() {
                if (!this.isLoading) {
                    this.clearAddBoxModal();
                }
                this.$root.$emit("bv::hide::modal", "neu-dataentry-part-modal");
            },
            rowClickedOk(data) {
                const box = this.getSelected(data.id);
                location.href = this.queryUrlBuilder(this.navUri, {
                    box: box[0].box_id,
                    "project": this.projectSelected.value
                });
            },
            clearAllDataForAddFunction() {
                this.dataAddPart = {};
                this.dataAddBox = [];
                for (let index in this.partIndexes) {
                    this.dataAddPart.push({part_index_value: "", id: this.partIndexes[index]["id"]});
                }
            },
            getSelected(data) {
                let box = [];
                box.push({box_id: data});
                return box;
            },
            setError(errorArr) {
                this.dismissCountDown = 5;
                this.errors = errorArr;
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },

        },
        mixins: [neuInputUtils, neuHandleProjectID],
        data() {
            return {
                waitingIndicator: null,
                waitingText: "",
                dataAddBox: [],
                dataAddPart: {},
                dataAddPartEmpty: [{part_index_value: ""}],
                editableColumns: [],
                editableColumns2: [],
                editablePartColumns: [],
                showTable: false,
                modalAddBox: false,
                modalAddPart: false,
                partIndexes: [],
                dataTable: [],
                dataAddChecked:[1],
                dataIndexTable: [],
                keyword: "",
                sortBy: "",
                sortDesc: false,
                dataColumns:
                    [
                        {
                            key: "id",
                            label: "Box Id",
                            visible: false,
                            sortable: false,
                            editable: false,
                            "type": "TEXT",
                            "slot": "true",
                            "size": "15"
                        },
                        {
                            key: "box_name",
                            label: "Box Number",
                            visible: true,
                            sortable: true,
                            editable: true,
                            "type": "TEXT",
                            "slot": "true",
                            "size": "15"
                        },
                        {
                            key: "box_type",
                            label: "Box Type",
                            visible: true,
                            sortable: true,
                            editable: true,
                            "type": "TEXT",
                            "slot": "true",
                            "size": "15"
                        },

                        {
                            key: "box_location_code",
                            label: "Location",
                            visible: true,
                            sortable: true,
                            sortDirection: "desc",
                            editable: true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "rfid",
                            label: "RFID",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "created_at",
                            label: "Created On",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            sortDirection: "desc",
                            "type": "DATE",
                            "slot": "false"
                        },
                        {
                            key: "created_by_name",
                            label: "Created By",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            sortDirection: "desc",
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "updated_by_name",
                            label: "Last Updated By",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            sortDirection: "desc",
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "part_count",
                            label: "Part Count",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "box_status",
                            label: "Status",
                            sortable: false,
                            "visible": false,
                            "editable": false,
                            "type": "TEXT",
                            "slot": "false"
                        }
                    ],
                dataBoxColumns:
                    [
                        {
                            key: "box_name",
                            label: "Name",
                            visible: true,
                            sortable: false,
                            editable: true,
                            "type": "TEXT",
                            "slot": "true",
                            "size": "15"
                        },
                        {
                            key: "box_location_code",
                            label: "Location",
                            visible: true,
                            sortable: true,
                            sortDirection: "desc",
                            editable: true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "rfid",
                            label: "RFID",
                            visible: true,
                            sortable: true,
                            sortDirection: "desc",
                            editable: true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "box_type",
                            label: "Box Type",
                            sortable: true,
                            "visible": false,
                            "editable": true,
                            "type": "DROPDOWN",
                            "options": []
                        }

                    ],
                dataPartColumns:
                    [
                        {
                            key: "part_name",
                            label: "Part",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "TEXT",
                            "slot": "false"
                        },


                        {
                            key: "media_type_id",
                            label: "Media Types",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "CHECKBOX",
                            "checkboxOptions": []
                        }
                    ],
                dataIndexColumns:
                    [
                        {
                            key: "part_index_value",
                            label: "Index",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            sortDirection: "desc",
                            "type": "TEXT",
                            "slot": "false"
                        }
                    ],
                errors: {},
                dismissCountDown: 0,
                resultBoxTemp: {},
                isLoading: false
            };
        }
    };
</script>
