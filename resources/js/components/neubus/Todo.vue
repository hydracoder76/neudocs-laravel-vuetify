<template>
    <div>
        <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
                             shadow-icon="cog"></neu-shadow-backdrop>
        <div class="col-md-8 offset-md-1">
            <slot name="neu-todo-content"></slot>
        </div>
        <b-alert dusk="statusCRUD" class="neu-alert" :show="dismissCountDown" :variant="statusVar" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
            <p>{{ statusMessage }}</p>
        </b-alert>
        <span v-show="waitingIndicator === false || inlineLoad === true">
     <neu-form dusk="todo-form" :submission-uri="submitTo" v-if="showTable">

     <b-modal id="modal-1" title="Fulfill Media Types" @ok="handleOk" size="lg">
            <h4 class="text-center">Are you sure?</h4>
            <template v-for="(part, key) in parts">
                <div class="text-center" :key="key">
                <template v-for="item in dataAddChecked[part['part_id']]" v-if="dataAddChecked[part['part_id']].length > 1">
                        <b-form-checkbox v-if="item == option.id"
                                        v-for="option in checkboxOptions"
                                        v-model = "mediaTypeEmpty[part['part_id']]"
                                        :key="option.id"
                                        :value="option.id"
                                        name="flavour-3a"
                        >
                            {{ option.type }}
                        </b-form-checkbox>
                    </template>
                </div>
            </template>

    </b-modal>

    <neu-table dusk="neu-todo-table" title=""
               type="edit"
               :request-uri="requestUri"
               :data-neu-table="dataTable"
               :columns="dataColumns"
               :create-new="false"
               :custom-add="true"
               :username="username"
               :no-local-sorting="true"
               :no-local-filtering="true"
               @neu-sort-changed="todoSort"
               @filter-ok="todoFilter"
               @clear-filter-ok="clearFilter"
               @page-change="getResults"
               class="vm-margin"
               sort-by-statement="requested_at"
               sort-direction="asc"
               :sort-descending="false"
               ref="status"
               @neu-check="todoCheck">
        <template slot="custom-add">
            <neu-button btn-size="lg" btn-text="Upload" dusk="neu-todo-upload-btn"
                        @neu-btn-click="goToPage('upload', uploadUri)" fa-icon="upload"></neu-button>
            <neu-button btn-size="lg" btn-text="Scan" id="neu-todo-scan-btn" @neu-btn-click="goToPage('scan', scanUri)"
                        fa-icon="print"></neu-button>
             <neu-button btn-size="lg" btn-text="Fulfill" id="neu-todo-fulfill-btn" @neu-btn-click="fulfill()" fa-icon="file"></neu-button>

        </template>
    </neu-table>
  </neu-form>
</span>

    </div>
</template>

<script>
    import axios from "axios";
    import NeuTable from "../util/NeuTable";
    import NavBar from "../NavBar";
    import NeuForm from "../forms/NeuForm";
    import NeuButton from "../util/NeuButton";
    import moment from "moment";
    import neuInputUtils from "../mixins/neu-input-utils";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";
    import neuHandleProjectID from "../mixins/neu-handle-projectID";
	import bAlert from "bootstrap-vue/es/components/alert/alert";
    import bModal from "bootstrap-vue/es/components/modal/modal";
    import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
    import neuMediaType from "./../mixins/neu-media-type";
	export const yellowConst = 10, redConst = 14;

    export default {
        name: "EditableTodoTable",
        components: {
            NeuTable, NavBar, NeuForm, NeuButton, NeuShadowBackdrop, bAlert, bModal, bFormCheckbox
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
            submitTo: {
                type: String,
                required: false,
                default: ""
            },
            navUri: {
                type: String,
                required: true,
            },
            uploadUri: {
                type: String,
                required: true,
            },
            scanUri: {
                type: String,
                required: true,
            },
            todoSearch: {
                type: String,
                required: true
            },
			fulfillUri: {
				type: String,
				required: true
			},
            mediaTypeLoad: {
                type: String,
                required: true
            },
            partMediaTypeLoad: {
                type: String,
                required: true
            }
        },
        created() {
            axios.get(this.mediaTypeLoad).then(mediaTypeResult => {
                this.checkboxOptions = mediaTypeResult.data.data[0];
            });
            
            this.$on("neu-item-selected", value => {
                if (value !== null && value !== "") {
                    this.projectIDSelected(value);
                    this.inlineLoad = false;
                    this.$root.$emit("neu-item-selected", value);
                }
            });
        },
        methods: {
            updatePageChange(page) {
                this.getResults(page);
            },
            getResults(page) {
                if (!this.projectSelected.value) {
                    this.$refs.status.showAlert(["No project selected"], "warning");
                    return false;
                }
                this.waitingIndicator = true;
                if (!this.isSort && !this.isFilter) {
                    axios.post(this.queryUrlBuilder(this.requestUri + "/" + this.projectSelected.value, {'page': page})).then(result => {
                        this.priorityYellow = result.data.data.yellow;
                        this.priorityRed = result.data.data.red;
                        this.dataTable = result.data.data.results;
                        this.statusColors();
                        this.updateSelectedParts();
                        this.dataTable['total'] = result.data.data.length;
                        this.dataTable['currentPage'] = page;
                        this.waitingIndicator = false;

                    }).catch(error => {
                        this.dataTable = [];
                        this.waitingIndicator = false;
                        if (this.projectSelected !== null) {
                            this.$refs.status.showAlert([error.response.data.message], "warning");
                        }
                    });
                } else {
                    this.todoSearchApi(page);
                }
            },
            todoSort(sortObj) {
                if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
                    this.isSort = true;
                    this.sortBy = sortObj.sortBy;
                    this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
                    this.todoSearchApi(1);
                }
            },
            todoFilter(keyword) {
                this.isFilter = true;
                this.keyword = keyword;
                this.sortBy = "";
                this.selectedParts = {};
                this.todoSearchApi(1);
            },
            todoSearchApi(page) {
                this.waitingIndicator = true;
                this.inlineLoad = true; // means that all requests here reload the table without replacing it, thus can be done without hiding
                axios.get(this.queryUrlBuilder(this.todoSearch, {
                    page: page, order: this.sortDesc, keyword: this.keyword,
                    sortBy: this.sortBy, projectId: this.projectSelected.value
                })).then(result => {
                    this.dataTable = result.data.data.results;
                    this.dataTable["total"] = result.data.data.length;
                    this.dataTable["currentPage"] = page;
                    this.updateSelectedParts();
                    this.statusColors();
                    this.waitingIndicator = false;
                }).catch(error => {
                    this.waitingIndicator = false;
                    this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
                });
            },
            updateSelectedParts() {
                for (let data in this.dataTable) {
                    const partId = this.dataTable[data].part_id;
                    const requestId = this.dataTable[data].request_id;
                    if (this.selectedParts.hasOwnProperty(partId) && typeof this.selectedParts[partId][requestId] !== "undefined") {
                        this.dataTable[data].select = true;
                    }
                }
            },
            clearFilter() {
                this.isFilter = false;
                this.isSort = false;
                this.sortBy = "";
                this.keyword = "";
                this.selectedParts = {};
                this.getResults(1);
            },
            statusColors() {
                let i = 0;
                for (const key of this.dataTable) {
                    const today = moment(new Date());
                    const requestedAtDate = moment(key.requested_at, "YYYY-MM-DD h:mm a");

                    const duration = moment.duration(today.diff(requestedAtDate));

                    const days = duration.asDays();

                    if (days <= this.priorityYellow)
                        this.dataTable[i].request_status = "green";
                    else if (days <= this.priorityRed)
                        this.dataTable[i].request_status = "yellow";
                    else
                        this.dataTable[i].request_status = "red";
                    i++
                }
            },
            goToPage(action, url) {
                const parts = this.getSelected();
                axios.post(this.navUri, {parts: parts, action: action}).then(result => {
                    if (action === "scan") {
                        const parts = result.data.data.parts;
                        localStorage.removeItem("todo_scan_parts");
                        localStorage.setItem("todo_scan_parts", JSON.stringify(parts));
                        const partKey = result.data.data.key;
                        location.href = this.queryUrlBuilder(url, {"partKey": partKey});
                    } else {
                        const parts = result.data.data.parts;
                        location.href = this.queryUrlBuilder(url, {
                            "parts": JSON.stringify(parts),
                            "project": this.projectSelected.value
                        });
                    }
                }).catch(error => {
                    console.log(error.response.data.errors);
                });
            },
			fulfill(){
				const parts = this.getSelected();
                this.parts = parts;
				if (parts.length <= 0){
					this.showAlert("Must select parts to fulfill", "warning");
					return;
				}
                this.$root.$emit("bv::show::modal", "modal-1", "#btnShow")
                this.getPartMediaTypes(this.partMediaTypeLoad, parts).then(values => {
                    this.dataAddChecked = values[0];
                    this.mediaTypeEmpty = values[1];
                });
			},
            handleOk() {
                const parts = this.getSelected();
                this.waitingIndicator = true;
                axios.post(this.fulfillUri, {parts:parts,  action: "fulfill", dataAddChecked: this.mediaTypeEmpty}).then(result => {
                    this.waitingIndicator = false;
                    this.getResults(this.dataTable["currentPage"]);
                    this.selectedParts = {};
                    this.showAlert("Fulfillment successful", "success");
                }).catch(error => {
                    console.log(error.response.data.errors);
                });
            },
            todoCheck(item) {
                if (item.select) {
                    if (!this.selectedParts.hasOwnProperty(item.part_id)) {
                        this.selectedParts[item.part_id] = {};
                    }

                    this.selectedParts[item.part_id][item.request_id] = true;
                    this.selectedPartsBox.push({part_id:item.part_id, box:item.box});
                } else {
                    if (typeof this.selectedParts[item.part_id][item.request_id] !== "undefined") {
                        delete this.selectedParts[item.part_id][item.request_id];
                        delete this.selectedPartsBox[this.selectedPartsBox.indexOf({part_id:item.part_id, box:item.box})];
                    }
                }
            },
            getSelected() {
                const parts = [];
                for (const part in this.selectedParts) {
                    for (const request in this.selectedParts[part]) {
                        parts.push({part_id: part, request_id: request});
                    }
                }
                return parts;
            },
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown;
			},
			showAlert(message,variant){
				this.dismissCountDown = this.dismissSecs;
				this.statusMessage = message;
				this.statusVar = variant;
			},
        },
        mixins: [neuInputUtils, neuHandleProjectID, neuMediaType],
        data() {
            return {
                projectSelected: {
                    value: ""
                },
                waitingIndicator: null,
                waitingText: "Loading...",
                inlineLoad: false,
                showTable: false,
                dataTable: [],
                checkboxOptions: [],
                dataAddChecked: [],
                mediaTypeEmpty:[],
                dataColumns:
                    [
                        {
                            key: "select",
                            label: "Select",
                            visible: true,
                            editable: true,
                            "type": "CHECKBOX",
                            "slot": "false"
                        },
                        {
                            key: "request",
                            label: "Request#",
                            visible: true,
                            sortable: true,
                            editable: true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "box",
                            label: "Box",
                            visible: true,
                            sortable: true,
                            editable: true,
                            "type": "TEXT",
                            "slot": "true",
                            "size": "15"
                        },
                        {
                            key: "part",
                            label: "Part",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "location",
                            label: "Location",
                            visible: true,
                            sortable: true,
                            sortDirection: "desc",
                            editable: true,
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "requested_at",
                            label: "Requested At",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            sortDirection: "desc",
                            "type": "TEXT",
                            "slot": "false"
                        },
                        {
                            key: "being_used_by",
                            label: "",
                            sortable: false,
                            "visible": true,
                            "editable": false,
                            "type": "FLAG",
                            "slot_header": "true"
                        },
                        {
                            key: "request_status",
                            label: "Request Priority",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "STYLE",
                            "slot": "true"
                        },
                        {
                            key: "completion",
                            label: "Completion",
                            sortable: true,
                            "visible": true,
                            "editable": false,
                            "type": "ICON",
                            "slot": "true"
                        },
                        {
                            key: "active_color",
                            label: "Active Color",
                            sortable: false,
                            "visible": false,
                            "editable": false,
                            "type": "FLAG",
                            "slot": "false"
                        },
                        {
                            key: "disabled_select",
                            label: "Disabled_select",
                            sortable: false,
                            "visible": false,
                            "editable": false,
                            "type": "FLAG",
                            "slot": "false"
                        },
                        {
                            key: "request_review",
                            label: "Request Review",
                            sortable: false,
                            "visible": true,
                            "editable": false,
                            "type": "TEXT"
                        },
                        {
                            key: "filename",
                            label: "Filename",
                            sortable: false,
                            "visible": true,
                            "editable": false,
                            "type": "STYLE",
                            "slot": "true"
                        },
                        {
                            key: "external_comment",
                            label: "External Comments",
                            sortable: false,
                            "visible": true,
                            "editable": false,
                            "type": "STYLE",
                            "slot": "true"
                        },

                        {
                            key: "media_type_id",
                            label: "Media Types",
                            sortable: true,
                            "visible": true,
                            "editable": true,
                            "type": "STYLE",
                            "slot": "true",
                            "checkboxOptions" : [

                            ]
                        }
                    ],
                priorityYellow: yellowConst,
                priorityRed: redConst,
                isSort: false,
                sortBy: "",
                sortDesc: false,
                isFilter: false,
                keyword: "",
                selectedParts: {},
                selectedPartsBox:[],
				dismissSecs:5,
				dismissCountDown:0,
				showDismissibleAlert:false,
				statusMessage:"",
				statusVar:"",
                parts: []
            };
        }
    };
</script>
