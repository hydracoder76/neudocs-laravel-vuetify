<template>
    <b-container fluid>
        <!-- Interface controls -->
        <b-row v-if="displayHeader">
            <b-col md="6" dusk="neu-heading" class="panel-heading">
                {{ title }}
            </b-col>
            <b-col md="6" class="my-1">
                <b-form-group horizontal class="mb-0 neu-search-field">
                    <b-input-group>
                        <input dusk="neu-filter-input" v-model="filter" class="neu-input-md neu-input" placeholder="Type to Search"/>
                        <span class="neu-clear-search-icon" v-show="filter" @click="clearFilterOk" >
							<font-awesome-icon :icon="getFaIconFromString('times')"></font-awesome-icon>
						</span>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>
        <div>
            <b-alert dusk="statusCRUD" class="neu-alert" :show="dismissCountDown" :variant="statusVar" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
                <p :key="$index" v-for="(message, $index) in statusMessage">{{ message }}</p>
            </b-alert>
        </div>
        <div class="neu-btn-cont" :class="createNew? 'neu-table-action-row': ''">
            <div>
                <slot v-if="customAdd" name="custom-add"></slot>
                <slot v-else-if="customAction" name="custom-action"></slot>
                <button type="button" v-if="createNew" class="neu-link-button neu-btn-cont-right neu-btn-md" dusk="neu-add-btn"
                        @click.stop="modalAdd=true">
                    <font-awesome-icon :icon="getFaIconFromString('plus')"></font-awesome-icon>
                    Create New {{ tabname }}</button>
                <slot name="add-box-button"></slot>
                <div dusk="neu-table-desc" class="pl-1">
                    {{ tabledesc }}
                </div>
            </div>
        </div>


        <!-- Main table element -->
        <b-table dusk="neu-table"
                 :items="dataShow"
                 :fields="showColumns"
                 :per-page="perPage"
                 :sort-by.sync="sortBy"
                 :sort-desc.sync="sortDesc"
                 :sort-direction="sortDirection"
                 :no-provider-filtering="noProviderFiltering"
                 :filter="filter"
                 :no-local-sorting="noLocalSorting"
                 :no-local-filtering="noLocalFiltering"
                 show-empty
                 :empty-text="emptyText"
                 @filtered="onFiltered"
                 @change="selectChange"
                 @row-clicked="rowClicked"
                 @sort-changed="tableSortChanged"
                 class="table table-bordered table-hover neu-table">
            <template slot="HEAD_being_used_by" slot-scope="data">
                <i class="fa fa-lock" ></i>
            </template>
            <template slot="select" slot-scope="row" >
                <input type="checkbox" name="check-record" v-if="row.item.disabled_select" v-model="row.item.select" @change="changeCheckbox(row.item, row.index, $event.target)">
                <i class="fas fa-lock" v-if="row.item.select"></i>
            </template>
            <template slot="selectPart" slot-scope="row">
                <b-form-checkbox v-model="row.item.selectPart" dusk="select-check" @change="partCheck($event, row.item)"></b-form-checkbox>
            </template>
            <template slot="external_comment" slot-scope="row">
                <font-awesome-icon @click.stop="msgModal(row.item.external_comment)" style="cursor: pointer;"
                                   color="green" size="2x"
                                   :icon="getFaIconFromString('comment')"
                                   v-if="row.item.external_comment !== '' && row.item.external_comment !== null"></font-awesome-icon>
            </template>
            <template slot="request" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.request }}</div>
            </template>
            <template slot="index" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.index }}</div>
            </template>
            <template slot="box" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.box }}</div>
            </template>
            <template slot="part" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.part }}</div>
            </template>
            <template slot="location" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.location }}</div>
            </template>
            <template slot="requested_at" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.requested_at }}</div>
            </template>
            <template slot="time" slot-scope="row">
                <div class="text-nowrap" :style="{color: row.item.active_color}">{{ row.item.time }}</div>
            </template>
            <template slot="request_status" slot-scope="row">
                <i class="fas fa-circle" :style="{color: row.item.request_status}"></i>
            </template>

            <template slot="media_type_id" slot-scope="row">
                <span v-for="(media, index) in row.item.media_type_id">
                    <span v-if="media.check === true" :key="index" class="pl-2">
                        <font-awesome-icon :icon="getFaIconFromString('check')"></font-awesome-icon>{{ media.type }}</span>
                    <span v-else :key="index" class="pl-2">
                        <font-awesome-icon :icon="getFaIconFromString('times')"></font-awesome-icon>{{ media.type }}</span>                   
                </span>             
            </template>
            <template slot="request_indexes" slot-scope="row">
                <span v-html="row.item.request_indexes"></span>
            </template>
            <template slot="completion" slot-scope="row">
                <font-awesome-icon v-if="row.item.completion=='partial'" :icon="getFaIconFromString('clock')"></font-awesome-icon>
            </template>
            <template slot="actions" slot-scope="row">
				<span class="neu-edit-icon" title="Edit user">
					<font-awesome-icon
                            @click.stop="edit(row.item, row.index, $event.target)"
                            :icon="getFaIconFromString('user-edit')">
					</font-awesome-icon>
				</span>

                <span class="neu-delete-icon" title="Delete user">
					<font-awesome-icon
                            @click.stop="del(row.item, row.index, $event.target)"
                            :icon="getFaIconFromString('trash-alt')">
					</font-awesome-icon>
				 </span>


            </template>
            <!-- add icons to cellsif needed-->
            <template slot="file_actions_upload" slot-scope="row">
				<span :class="row.value.styles">
				<font-awesome-icon
                        @click.stop="iconClicked(row.value.eventName, row.value.rowId)" v-if="!row.value.isActing"
                        :icon="getFaIconFromString(row.value.icon)"></font-awesome-icon>
				<font-awesome-icon v-if="row.value.isActing" :icon="getFaIconFromString(row.value.eventPlaceHolderIcon)" spin
                                   :ref="row.value.rowId"></font-awesome-icon>
				</span>
            </template>
            <template slot="download" slot-scope="row">
                <template v-if="row.item.download == 'request'"><a target="_self" :href="getDownloadUrl(row.item.request_id)">Download</a></template>
                <template v-else-if="row.item.download == 'part'"><a target="_self" :href="getDownloadUrl(row.item.part_id, row.item.request)">Download</a></template>
                <template v-else-if="row.item.download === 'box'"><a target="_self" :href="getDownloadUrl(row.item.file_id)">Download</a></template>
                <template v-else>Not ready</template>
            </template>
            <template slot="reset_password" slot-scope="row">
				<span v-if="showReset(row.item)">
					<button type="button" class="neu-link-button neu-btn-sm" @click="resetPassword(row.item.email)" :dusk="'neu-reset-pass-' + row.item.id">Reset</button>
				</span>
            </template>
            <template slot="comment" slot-scope="row">
                <textarea v-model="row.item.commentText"></textarea>
            </template>
            <template slot="review" slot-scope="row">
				<span class="neu-action-icon">
					<font-awesome-icon :icon="getFaIconFromString('check')" @click.stop="iconClicked('neu-accept-review', row.index)"></font-awesome-icon>
				</span>
                <span class="neu-delete-icon">
					<font-awesome-icon :icon="getFaIconFromString('times-circle')" @click.stop="iconClicked('neu-reject-review', row.index)"></font-awesome-icon>
				</span>
            </template>
            <template slot="filename" slot-scope="row">
                <template v-for="(file, index ) in row.item.filename" >
					<span v-if="file.is_scanned" :key="index">
						<font-awesome-icon :icon="getFaIconFromString('print')"></font-awesome-icon> {{ file.name }}</span>
                    <span v-else :key="index">
						<font-awesome-icon :icon="getFaIconFromString('upload')"></font-awesome-icon> {{ file.name }}</span>
                    <br>
                </template>
            </template>
            <template slot="details" slot-scope="row">
                <p v-if="row.item.details.message">{{ row.item.details.message }}</p>
                <span :id="'popover'+row.index" class="neu-view-icon">
						<font-awesome-icon :icon="getFaIconFromString('eye')"></font-awesome-icon>
					</span>
                <b-popover triggers="hover focus" ref="popover" :target="'popover'+row.index" title="Details">
                    <ul class="list-unstyled">
                        <li v-for="(val, key, $index) in row.item.details.fields" :key="$index" class="neu-log-row">{{ val }}</li>
                    </ul>
                </b-popover>

            </template>

            <slot name="custom-neutable-content"></slot>
        </b-table>
        <b-row>
            <b-col md="6" class="my-1">
                <b-pagination v-if="totalRows / perPage > 1" dusk="neu-pagination" :total-rows="totalRows" :per-page="perPage" v-model="currentPage" @change="pageChange" class="my-0"/>
            </b-col>
        </b-row>

        <!-- Info modal -->
        <b-modal dusk="modalAdd" title="Add" v-model="modalAdd" ref="addRef" no-close-on-backdrop @ok="addOk" button-size="lg"
                 body-bg-variant="lightgray" header-bg-variant="lightgray" ok-variant="lightblue" @cancel="resetAddModal">


            <b-form-group v-for="(item) in editableColumns" :label="item.label" :key="item.id">
                <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
                    <input :dusk="item.key" v-model.trim="dataAdd[item.key]" :type="item.type" class="neu-input neu-input-lg"/>
                </template>
                <template v-else-if="item.type === 'DROPDOWN'">
                    <b-form-select :dusk="item.key" :options="item.options" v-model = "dataAdd[item.key]" class="neu-input neu-input-lg"
                                   @input="inputSelect(dataAdd[item.key],item.key)" />
                </template>

                <template v-else-if="item.type === 'CHECKBOX'">
                    <b-form-checkbox :disabled="option.value == 1"
                     v-for="option in item.checkboxOptions"
                        v-model = "dataAddChecked"
                        :key="option.value"
                        :value="option.value"
                        name="flavour-3a"
                    >
                        {{ option.text }}
                    </b-form-checkbox>
                </template>
            </b-form-group>

        </b-modal>
        <!-- TODO: this item.type conditional stuff needs to go, this is not in the spec. Please use actual input fields
        -->
        <b-modal dusk="modalEdit" title="Edit" v-model="modalEdit" ref="editRef" no-close-on-backdrop @ok="editOk" button-size="lg"
                 body-bg-variant="lightgray" header-bg-variant="lightgray" ok-variant="lightblue">
            <b-form-group v-for="(item) in editableColumns" :label="item.label" :key="item.id">
                <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
                    <input :dusk="item.key" v-model.trim="dataEdit[item.key]" :type="item.type" class="neu-input neu-input-lg"/>
                </template>
                <template v-else-if="item.type === 'DROPDOWN'">
                    <b-form-select :dusk="item.key" :options="item.options" v-model = "dataEdit[item.key]" class="neu-input neu-input-lg" @input="inputSelect(dataEdit[item.key],item.key)" />
                </template>

                <template v-else-if="item.type === 'CHECKBOX'">
                    <b-form-checkbox :disabled="option.value === 1"
                                     v-for="option in item.checkboxOptions"
                                     v-model = "dataEdit[item.key]"
                                     :key="option.value"
                                     :value="option.value"
                                     name="flavour-3a"
                    >
                        {{ option.text }}
                    </b-form-checkbox>
                </template>
            </b-form-group>

        </b-modal>
        <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
            <pre>{{ modalInfo.content }}</pre>
        </b-modal>
        <b-modal dusk="modalDelete" v-model="modalDelete" title="Delete" @ok="deleteOk">
            <p class="my-4">Are you sure to delete this data?</p>
        </b-modal>
        <b-modal dusk="modalMsg" title="External Comments" v-model="msgModalShow" ok-only>
            {{ msgModalContent }}
        </b-modal>


    </b-container>
</template>

<script>

    import bModal from "bootstrap-vue/es/components/modal/modal";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
    import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
    import bInputGroupAppend from "bootstrap-vue/es/components/input-group/input-group";
    import bRow from "bootstrap-vue/es/components/layout/row";
    import bPagination from "bootstrap-vue/es/components/pagination/pagination";
    import bCol from "bootstrap-vue/es/components/layout/col";
    import bCard from "bootstrap-vue/es/components/card/card";
    import bButton from "bootstrap-vue/es/components/button/button";
    import bTable from "bootstrap-vue/es/components/table/table";
    import bContainer from "bootstrap-vue/es/components/layout/container";
    import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
    import bAlert from "bootstrap-vue/es/components/alert/alert";
    import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
    import bPopover from "bootstrap-vue/es/components/popover/popover";
    import neuButton from "./NeuButton";
    import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
    import neuInputUtils from "../mixins/neu-input-utils";
    import neuUtils from "../mixins/neu-utils";


    // add needed icons here
    import {
        faEye,
        faTrashAlt,
        faUserEdit,
        faCog,
        faClock,
        faCircle,
        faTimesCircle,
        faCheck,
        faPrint,
        faUpload,
        faTimes,
        faPlus,
        faCommentDots
    } from "@fortawesome/free-solid-svg-icons";


    export default {
        components: {
            bModal, bFormGroup, bFormInput, bRow, bPagination, bCol,
            bCard, bButton, bTable, bContainer, bInputGroup, bInputGroupAppend, bFormSelect, bAlert, bFormCheckbox,
            FontAwesomeIcon, neuButton, bPopover
        },
        mixins: [neuInputUtils, neuUtils],
        props: {
            title: {
                type: String,
                required: false,
                default: ""
            },
            tabname: {
                type: String,
                required: false,
                default: ""
            },
            tabledesc:{
                type: String,
                required: false,
                default: ""
            },
            username: {
                type: String,
                required: false,
                default: ""
            },
            sortByStatement: {
                type: String,
                required: false,
                default: "time"
            },
            sortDirection: {
                type: String,
                required: false,
                default: "desc"
            },
            sortDescending: {
                type: Boolean,
                required: false,
                default: true
            },
            // TODO: both type and data are also used as keys in any vue component, these prop names need to be changed
            type: String,
            status: String,
            columns: Array,
            dataNeuTable: Array,
            customAdd: {
                type: Boolean,
                required: false,
                default: false
            },
            customAction: {
                type: Boolean,
                required: false,
                default: false
            },
            createNew: {
                type: Boolean,
                required: false,
                default: true
            },
            displayHeader: {
                type: Boolean,
                required: false,
                default: true
            },
            noProviderFiltering: {
                type: Boolean,
                required: false,
                default: false
            },
            downloadUrl:{
                type:String,
                required:false,
                default:""
            },
            perPage:{
                type: Number,
                required: false,
                default: 25
            },
            userCompany:{
                type: String,
                required: false,
                default: ""
            },
            userRole: {
                type: String,
                required: false,
                default: ""
            },
            noLocalSorting: {
                type: Boolean,
                required: false,
                default: false
            },
            noLocalFiltering: {
                type: Boolean,
                required: false,
                default: false
            },
            allPaginate: {
                type: Boolean,
                required: false,
                default: false
            },
            emptyText: {
                type: String,
                default: "There are no records to show"
            }
        },
        data() {
            return {
                activeColor: "black",
                currentPage: 1,
                filter: null,
                modalInfo: {title: "", content: ""},
                deleteDisabled: true,
                dataShow: [],
                showStripe: false,
                tableSize: "default",
                keyword: "",
                modalEdit: false,
                modalAdd: false,
                modalDelete: false,
                dataEdit: {},
                dataDelete: [],
                dataAdd: {},
                dataAddChecked:[1],
                formData: [],
                tempItem: [],
                dismissSecs:5,
                dismissCountDown:0,
                showDismissibleAlert:false,
                statusMessage:"",
                statusVar:"",
                editableColumns:[],
                totalRows: 0,
                sortDesc: this.sortDescending,
                sortBy: this.sortByStatement,
                isFiltering: false,
                msgModalContent: "",
                msgModalShow: false
            };
        },
        computed: {
            showColumns: function () {
                const showColumn = this.columns,
                      showColumn2 = [];
                showColumn.forEach(function (elem) {
                    if(elem.visible) {
                        showColumn2.push(elem);
                    }
                });
                return showColumn2;
            }
        },
        methods: {
            iconClicked(eventName, eventSuffix) {
                this.$emit(eventName, eventSuffix);
            },
            // eslint-disable-next-line complexity
            getFaIconFromString(iconName) {
                switch (iconName) {
                case "trash-alt":
                    return faTrashAlt;
                case "cog":
                    return faCog;
                case "circle":
                    return faCircle;
                case "clock":
                    return faClock;
                case "check":
                    return faCheck;
                case "times-circle":
                    return faTimesCircle;
                case "print":
                    return faPrint;
                case "upload":
                    return faUpload;
                case "plus":
                    return faPlus;
                case "times":
                    return faTimes;
                case "eye":
                    return faEye;
                case "user-edit":
                    return faUserEdit;
                case "comment":
                    return faCommentDots;
                default:
                    return "";
                }
            },
            filterOk() {
                if(this.filter !== null && this.filter !== "") {
                    this.$emit("filter-ok", this.filter);
                }else if (!this.isFiltering) {
                    this.clearFilterOk();
                }
                this.isFiltering = true;
            },
            clearFilterOk () {
                this.filter = "";
                this.$emit("clear-filter-ok", this.filter);
                this.isFiltering = true;
            },
            editOk() {
                this.$emit("edit-ok", this.dataEdit);
            },
            edit(item, index, button) {
                this.modalEdit = true;
                this.dataEdit = this.handleTableData(item);
                this.$root.$emit("bv::show::modal", "modalEdit", button);
            },
            selectChange(data) {
                this.dataDelete = data;
            },
            del(item, index, button) {
                this.dataDelete.push(item);
                this.modalDelete = true;
                this.$root.$emit("bv::show::modal", "modalDelete", button);
            },
            msgModal(msg) {
                this.msgModalContent = msg;
                this.msgModalShow = true;
                this.$root.$emit("bv::show::modal", "modalMsg");
            },
            resetModal() {
                this.modalInfo.title = "";
                this.modalInfo.content = "";
            },
            resetAddModal(){
                for (const item in this.editableColumns) {
                    this.dataAdd[this.editableColumns[item].key] = "";
                }
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                if(this.filter !== null && this.filter !== "" && !this.noLocalFiltering){  // if filter mode is true
                    this.totalRows = filteredItems.length;
                    this.currentPage = 1;
                }else {
                    this.totalRows = this.dataNeuTable.total;
                    this.currentPage = this.dataNeuTable.currentPage;
                }
            },
            rowClicked (item, index, event) {
                this.dataEdit = this.handleTableData(item);
                this.$emit("row-click-ok", this.dataEdit);
            },
            deleteOk(){
                this.$emit("delete-ok", this.dataDelete);
            },
            addOk() {
                const tempData = JSON.parse(JSON.stringify(this.dataAdd));
                tempData.media_type_id = this.dataAddChecked;
                this.$emit("add-ok", tempData);
                this.resetAddModal();
            },
            countDownChanged(dismissCountDown){
                this.dismissCountDown = dismissCountDown;
            },
            showAlert(message, variant){
                this.dismissCountDown = this.dismissSecs;
                this.statusMessage = message;
                this.statusVar = variant;
            },
            handleColumns(editableColumn) {
                const editableColumn2 = editableColumn.filter((item) => {
                    return item.editable && item;
                });
                return editableColumn2;
            },
            handleTableData(columns) {
                const allowed = this.columns
                          .filter(function (obj) {
                              return obj.editable === true;
                          })
                          .map(function (obj) {
                              return obj.key;
                          }),

                      showData2 = Object.keys(columns)
                          .filter(key => allowed.includes(key))
                          .reduce((obj, key) => {
                              obj[key] = columns[key];
                              return obj;
                          }, {});
                Vue.set(showData2, "id", columns.id);
                return showData2;
            },
            pageChange(page) {
                this.currentPage = page;
                this.$emit("page-change", page);
                const startPage = (this.currentPage - 1) * this.perPage,
                      endPage = startPage + this.perPage;
                this.dataShow = this.dataNeuTable.slice(startPage, endPage);
            },
            tableSortChanged(sortObj){
                this.$emit("neu-sort-changed", sortObj);
            },
            formatMessageForErrorAlert(errorObj) {
                let errorList = [];
                const errors = Object.keys(errorObj).forEach(errorKey => {
                    const specificErrorList = [];
                    errorObj[errorKey].forEach(item => {
                        specificErrorList.push(item);
                    });
                    errorList = errorList.concat(specificErrorList);

                });
                this.showAlert(errorList, "warning");
            },
            changeCheckbox(item, index, checkbox) {
                if (!item.select) {
                    item.being_used_by = "";
                    item.active_color = "black";
                }
                else {
                    item.active_color = "gray";
                    item.being_used_by = this.username;
                }
                this.$emit("neu-check", item);
            },
            inputSelect(value, key) {
                if(this.tabname === "Project") {
                    this.$emit("input-select", value);
                }

            },
            getDownloadUrl(requestId, requestName){
                const urlParams = {
                    downloadId: requestId
                };
                if (requestName) {
                    urlParams.requestName = requestName;
                }
                return this.queryUrlBuilder(this.downloadUrl, {downloadId:requestId, requestName: requestName});
            },
            partCheck(value, part){
                this.$emit("part-check", value, part);
            },
            showReset(value){
                if (this.userRole === this.userRoles.it || (this.userRole === this.userRoles.admin && this.userCompany ===
                    value.company_name && value.role !== this.userRoles.neubus && value.role !== this.userRoles.it)){
                        return true;
                    }
                return false;
            },
            resetPassword(email){
                this.$emit("neu-reset-password", email);
            }
        },
        watch: {
            dataNeuTable(){
                this.totalRows = this.dataNeuTable.total;
                this.currentPage = this.dataNeuTable.currentPage;
                if (this.allPaginate) {
                    const startPage = (this.currentPage - 1) * this.perPage,
                          endPage = startPage + this.perPage;
                    this.dataShow = this.dataNeuTable.slice(startPage, endPage);
                }
                else {
                    this.dataShow = this.dataNeuTable.slice(0, this.perPage);
                }
            },
            dataDelete(){
                if (this.dataDelete.length === 0) {
                    this.deleteDisabled = true;
                } else {
                    this.deleteDisabled = false;
                }
            },
            filter(newFilter, oldFilter){

                this.debounceGetAnswer();
            }
        },
        created(){
            this.debounceGetAnswer = _.debounce(() => {
                this.filterOk();
                setTimeout(() => {
                    this.isFiltering = false;
                }, 1000);
            }, 500);
        },
        mounted: function () {
            this.editableColumns = this.handleColumns(this.columns);
            this.dataShow = this.dataNeuTable.slice(0, this.perPage);

        }
    };
</script>
