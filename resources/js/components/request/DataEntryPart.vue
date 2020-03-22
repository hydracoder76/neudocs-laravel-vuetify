<template>
  <div>
    <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText" shadow-icon="cog"></neu-shadow-backdrop>
    <neu-form v-show="waitingIndicator === false" dusk="neu-dataentry-form" :submission-uri="submitTo">
      <neu-table dusk="dataentry-neu-table" title=""
                 type="edit"
                 ref="status"
                 :request-uri="requestUri"
                 :data-neu-table="dataTable"
                 :columns="dataColumns"
                 :create-new="false"
                 :username="username"
                 :no-local-sorting="true"
                 :no-local-filtering="true"
                 class="vm-margin"
                  @page-change="changePage"
                 @neu-sort-changed="partSort"
                 @filter-ok="partFilter"
                 @clear-filter-ok="clearFilter">
        <template slot="add-box-button">
          <b-button type="button" dusk="add-part-button" @click="showAddPart">Add Part</b-button>
        </template>


      </neu-table>
    </neu-form>

    <!-- Info Add Part modal -->
    <neu-form dusk="neu-dataentry-part-modal"
              :is-modal="true"
              neu-form-modal-id="neu-dataentry-part-modal"
              @neu-submit="submitNewPart"
              modal-size="lg"
              :hide-modal-footer="true">
      <template slot="neu-form-modal-title">
        Add Part {{ dataAdd["part_name"] }}
      </template>
      <template slot="neu-modal-form-content">
        <b-alert dusk="statusCRUD" :show="dismissCountDown" variant="warning" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
          <p v-for="(message, key) in errors">{{ message }}</p>
        </b-alert>
          <b-form-group v-for="(item) in editableColumns" horizontal :label="item.label" :key="item.id">
            <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
              <input :dusk="item.key" v-model.trim="dataAdd[item.key]" :type="item.type" class="neu-input neu-input-lg"/>
            </template>
            <template v-else-if="item.type === 'DROPDOWN'">
              <b-form-select :dusk="item.key" :options="item.options" v-model = "dataAdd[item.key]" class="neu-input neu-input-lg" @input="inputSelect(dataAdd[item.key],item.key)" />
            </template>
          </b-form-group>
        <div slot="modal-footer">
          <div class="float-left">
            <neu-button btn-size="md" btn-text="Cancel" btn-type="cancel" dusk="neu-dataentry-cancel-index-btn"
                        @neu-btn-click="cancelPart"></neu-button>
          </div>
          <div class="float-right">
            <template v-if="!isLoading">
              <neu-button btn-size="md" btn-type="action" btn-text="Add" dusk="neu-dataentry-save-index-btn"
                          @neu-btn-click="submitNewPart"></neu-button>
            </template>

            <template v-else>
              <neu-button btn-size="md" btn-type="action" btn-text="Loading..." dusk="neu-dataentry-save-index-btn"></neu-button>
            </template>
          </div>
        </div>
      </template>
    </neu-form>


    <div class="neu-btn-cont">
      <neu-button btn-text="Back to Box List" :btn-href="prevUrl"></neu-button>
    </div>

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
	import bButton from "bootstrap-vue/es/components/button/button";
	import bContainer from "bootstrap-vue/es/components/layout/container";
	import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
	import NeuButton from "../util/NeuButton";
	import axios from "axios";
	import neuInputUtils from "../mixins/neu-input-utils";
    import bAlert from "bootstrap-vue/es/components/alert/alert";
    import NeuShadowBackdrop from "../util/NeuShadowBackdrop";

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
      NeuShadowBackdrop
    },
    props: {
      boxJson:{
        type:String,
        required:false,
        default: ""
      },
      projectJson:{
        type:String,
        required:false,
        default: ""
      },
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
      dataEntrySubmissionUri: {
        type: String,
        required: true
      },
      dataEntryBoxSubmissionUri: {
        type: String,
        required: true
      },
      partindexUri: {
        type: String,
        required: true
      },
      partindexSchemaUri: {
        type: String,
        required: true
      },
      prevUrl: {
      	type:String,
        required:true
      }
    },
    created() {
      this.initialLoad();
      const enterClickHandler = (e) => {
          if (e.key === "Enter") {
            e.preventDefault();
            if (this.modalAddPartVisible) {
              this.submitNewPartCheck();
            }
        }
      };
      document.addEventListener('keydown', enterClickHandler);
      this.$once('hook:destroyed', () => {
        document.removeEventListener('keydown', enterClickHandler);
      });
    },
    mount(){
      this.editableColumns = this.handleColumns(this.column);
    },
    mounted() {
        this.$root.$on("bv::modal::hide", () => {
            this.modalAddPartVisible = false;
        });
    },
    methods: {
      submitNewPartCheck() {
          if (!this.isLoading) {
              this.submitNewPart();
          }
      },
      concatUri(uri, boxId) {
        return uri + "/" + boxId;
      },
      initialLoad() {
        this.waitingIndicator = true;
        axios.all([
          axios.get(this.queryUrlBuilder(this.partindexUri, {
            boxId: this.boxJson,
            page: 1,
            keyword: "",
            sortBy: "",
            order: "desc"
          })),
          axios.post(this.concatUri(this.partindexSchemaUri, this.projectJson))
        ])
                .then(axios.spread((partindexResult, schemaResult) => {
                  this.dataTable = partindexResult.data.data.result;
                  this.dataColumns = schemaResult.data.data.result[0];
                  this.dataTable.total = partindexResult.data.data.total;
                  this.dataTable.currentPage = 1;
                  this.maxPartNum = partindexResult.data.data.maxPartNum;
                  this.waitingIndicator = false;
                }))
                .catch(error => {
                  this.waitingIndicator = false;
                  this.$refs.status.showAlert([error.response.data.message], "warning");
                });
      },
      changePage(page) {
        axios.get(this.queryUrlBuilder(this.partindexUri, {boxId: this.boxJson, page: page, keyword: this.keyword,
          sortBy: this.sortBy, order: this.sortDesc})).then(partIndexResult => {
          this.dataTable  = partIndexResult.data.data.result;
          this.dataTable.total = partIndexResult.data.data.total;
          this.dataTable.currentPage = page;
          this.maxPartNum = partIndexResult.data.data.maxPartNum;
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },
      partSort(sortObj){
        this.isSort = true;
        this.sortBy = sortObj.sortBy;
        this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
        this.changePage(1);
      },
      partFilter(keyword){
        this.isFilter = true;
        this.keyword = keyword;
        this.sortBy = "";
        this.changePage(1);
      },
      clearFilter(){
        this.isFilter = false;
        this.isSort = false;
        this.sortBy = "";
        this.keyword = "";
        this.changePage(1);
      },
      submitNewPart() {
        this.isLoading = true;
        Vue.set(this.dataAdd, "box_id", this.boxJson);        
        axios.post(this.partSubmissionUri, {
          part_name: this.dataAdd.part_name,
          box_id: this.dataAdd.box_id,
          project_id: this.projectJson
        }).then(resultPart => {
          this.callbackNewIndex(resultPart);
        }).catch(error => {
          if (typeof (error.response.data.errors) === "object") {
            this.setError(error.response.data.errors);
          }else{
            this.setError([error.response.statusText]);
          }
          this.clearAddBoxModal();
          this.isLoading = false;
        });
      },
      callbackNewIndex(response) {
		  Vue.set(this.dataAdd, "part_id", response.data.data.id);
		  Vue.set(this.dataAdd, "created_by", response.data.data.created_by);
		  Vue.set(this.dataAdd, "created_at", response.data.data.created_at);
        for(const index in this.editableColumns) {
          if (!this.dataAdd.hasOwnProperty(this.editableColumns[index].key)) {
			  Vue.set(this.dataAdd, this.editableColumns[index].key, "");
          }
        }
        const data = JSON.parse(JSON.stringify(this.dataAdd));
        axios.post(this.partIndexSubmissionUri, {data}).then(result => {
          this.addPartOk();
          this.$refs.status.showAlert([result.data.message], "success");
        }).catch(error => {
          this.$refs.status.showAlert(error.response.data.errors, "warning");
        });
      },
      clearAddBoxModal() {
        this.dataAdd = {};
      },
      clearAddPartModal() {
        this.dataAdd = {};
        this.dataAdd.push(this.dataAddPartEmpty[0]);
      },
      showAddBox() {
        this.editableColumns = this.handleColumns(this.dataBoxColumns);
				this.$root.$emit("bv::show::modal", "neu-dataentry-box-modal");
			},
			handleColumns(editableColumn) {
				const editableColumn2 = editableColumn.filter((item) => {
					return item.editable && item;
				});
				return editableColumn2;
			},
			showAddPart() {
                if(  this.dataColumns.length === 0 ){
                  this.$refs.status.showAlert(["There are no indexes for this project"], "warning");
                  return false;
                }

				this.editableColumns = this.handleColumns(this.dataColumns);
				const l = this.maxPartNum;

				if (l === 0) {
					Vue.set(this.dataAdd, "part_name", 1);
				}else {
					Vue.set(this.dataAdd, "part_name", l + 1);
				}

				this.$root.$emit("bv::show::modal", "neu-dataentry-part-modal");
				this.modalAddPartVisible = true;
				return true;
			},
			showAddPartMore() {
				this.dataAdd.push({part_index_value: ""});
			},
			addBoxOk() {
				this.dataTable.push(this.dataAdd);
				this.clearAddBoxModal();
				this.$root.$emit("bv::hide::modal", "neu-dataentry-box-modal");
			},
			cancelBox() {
				this.clearAddBoxModal();
				this.$root.$emit("bv::hide::modal", "neu-dataentry-box-modal");
			},
			addPartOk() {
				this.dataTable.push(this.dataAdd);
				this.dataTable.total += 1;
				this.maxPartNum += 1;
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
			removeIndex(index) {
				this.dataAdd.rows.splice(index, 1);
			},
			itemSelected(projectObj) {
				this.showTable = true;
				this.projectSelected = projectObj;
				axios.post(this.dataEntryBoxSubmissionUri + "/" + this.projectSelected.value).then(result => {
					this.dataTable = result.data.data.result.data;
				}).catch(error => {
                  this.errors = error.response.data.errors;
              });
            },
            setError(errorArr){
              this.dismissCountDown = 5;
              this.errors = errorArr;
            },
            countDownChanged(dismissCountDown){
              this.dismissCountDown = dismissCountDown;
            },
    },
    mixins: [neuInputUtils],
    data () {
      return {
        dataAdd: {},
        dataAddPartEmpty: [{part_index_value: ""}],
        editableColumns: [],
        editableColumns2: [],
        showTable: false,
        modalAddBox: false,
        modalAddPart: false,
        dataTable: [],
        dataColumns: [],
        maxPartNum: 0,
        keyword: "",
        sortBy: "",
        sortDesc: false,
        errors: {},
        dismissCountDown: 0,
        isLoading: false,
        modalAddPartVisible: false,
        waitingIndicator: null,
        waitingText: "Loading Parts"
      }
    }
  }

</script>
