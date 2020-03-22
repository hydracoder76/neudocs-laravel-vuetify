<template>
  <div class="col-md-11">
    <div class="col-md-8 offset-md-2">
      <div>
        <b-alert class="col-md-10 offset-md-2" dusk="statusCRUD" :show="dismissCountDown" :variant="statusVar" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
          <p v-for="(message, $index) in statusMessage" :key="$index">{{ message }}</p>
        </b-alert>
      </div>
      <neu-form dusk="formAdd" @submit.prevent="submitNewLocation" :submission-uri="submitTo">
        <b-form-group v-for="(item) in editableColumns" horizontal
                      :label="item.label + ': '"
                      :key="item.id"
                      label-text-align="right">
          <template v-if="item.type === 'TEXT' || item.type === 'PASSWORD'">
            <input :dusk="item.key" v-model.trim="dataAdd[item.key]" :type="item.type" class="neu-input neu-input-lg"/>
          </template>
        </b-form-group>
        <div class="float-right">
          <neu-button btn-size="md" btn-type="action" btn-text="Done" dusk="neu-location-save-btn"
                      @neu-btn-click="submitNewLocation"></neu-button>
        </div>
      </neu-form>
    </div>
  </div>


</template>

<script>
	import bModal from "bootstrap-vue/es/components/modal/modal";
	import bAlert from "bootstrap-vue/es/components/alert/alert";
	import bForm from "bootstrap-vue/es/components/form/form";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
	import bInputGroupAppend from "bootstrap-vue/es/components/input-group/input-group";
	import NeuTable from "../util/NeuTable"
	import NavBar from "../NavBar"
	import NeuForm from "../forms/NeuForm"
	import moment from "moment";
	import bButton from "bootstrap-vue/es/components/button/button";
	import bContainer from "bootstrap-vue/es/components/layout/container";
	import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
	import NeuButton from "../util/NeuButton";
	import axios from "axios"
	import neuInputUtils from "../mixins/neu-input-utils";

	export default {
		name: "UpdateLocation",
		components: {
			NeuTable,
			NavBar,
			NeuForm,
			bButton,
			bContainer,
			bAlert,
			bFormGroup,
			bFormInput,
			bInputGroup,
			bInputGroupAppend,
			bFormSelect,
			NeuButton,
			bForm,
			bModal
		},
		props: {
			locationUri: {
				type: String,
				required: true
			},
			submitTo: {
				type: String,
				required: true
			}
		},
		mounted() {
			this.editableColumns = this.handleColumns(this.dataColumns)
		},
		methods: {
			handleColumns(editableColumn) {
				const tempEditableColumn = editableColumn.filter((item) => {
					return item.editable && item
				})
				return tempEditableColumn
			},
			submitNewLocation() {
				axios.post(this.locationUri, {
					activity: this.dataAdd.activity,
					box_name: this.dataAdd["box-name"],
					location: this.dataAdd.location
				}).then(result => {
					this.showAlert([result.data.message], "success")
					this.resetAddForm()
				}).catch(error => {
					if(error.response.status === 404){
						this.showAlert([error.response.data.message], "danger")
					}else{
						this.formatMessageForErrorAlert(error.response.data.errors);
					}
				});
			},
			resetAddForm() {
				for (const item in this.editableColumns){
					this.dataAdd[this.editableColumns[item].key] = "";
				}
			},
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown
			},
			showAlert(message, variant){
				this.dismissCountDown = this.dismissSecs
				this.statusMessage = message
				this.statusVar = variant
			},
			formatMessageForErrorAlert(errorObj) {
				let errorList = [];
				const errors = Object.keys(errorObj).forEach(errorKey => {
					const specificErrorList = [];
					errorObj[errorKey].forEach(item => {
						specificErrorList.push(item);
					});
					errorList = errorList.concat(specificErrorList)

				});
				this.showAlert(errorList, "danger");
			}
		},
		data () {
			return {
				dataAdd: [],
				editableColumns:[],
				dismissSecs:5,
				dismissCountDown:0,
				showDismissibleAlert:false,
				statusMessage:"",
				statusVar:"",
				dataTable: [],
				dataColumns: [
					{ key: "box-name", label: "Box Name", "visible" : true, "editable" : true, "type" : "TEXT"},
					{ key: "activity", label: "Activity", "visible": true, "editable" : true, "type" : "TEXT" },
					{ key: "location", label: "Location", "visible" : true, "editable" : true, "type" : "TEXT" }
				]
			}
		}
	}

</script>
