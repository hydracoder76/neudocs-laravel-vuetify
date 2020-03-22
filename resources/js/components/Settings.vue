<template>
    <div class="col-md-11">
        <div class="col-md-8 offset-md-1">
            <slot name="neu-todo-content"></slot>
        </div>
        <div v-if="showSettings">
            <b-alert :show="dismissCountDown" :variant="statusVar" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">{{statusMessage}}</b-alert>
            <div class="row settingRow" v-for="(setting, key, index) in settings">
                <label :for="index + '_setting'" class="settingLabel">{{setting.label}}</label>
                <input :id="index + '_setting'" type="text" v-model="settings[key].value">
            </div>
            <div class="neu-btn-cont">
                <div class="neu-btn-cont-right">
                    <neu-button btn-text="Save Settings" @neu-btn-click="saveSettings"></neu-button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .settingLabel{
        margin-right:15px;
    }
    .settingRow{
        margin-bottom:15px;
        margin-left:auto;
        display:block;
    }
</style>
<script>
	import axios from "axios";
	import neuButton from "./util/NeuButton";
	import bAlert from "bootstrap-vue/es/components/alert/alert";
	import neuInputUtils from "./mixins/neu-input-utils";
	import neuHandleProjectID from "./mixins/neu-handle-projectID";

	export default {
		data () {
			return {
                settings:{},
                dismissCountDown:0,
				statusVar:'success',
				statusMessage:'',
                dismissSecs:5,
				projectSelected: {
					value: ""
				},
                showSettings:false,
		    }
		},
        created(){
			this.$on("neu-item-selected", value => {
				if (value !== null) {
					this.projectIDSelected(value);
					this.$root.$emit('neu-item-selected', value)
				}
			});
        },
		methods: {
			updatePageChange(page){
				axios.get(this.queryUrlBuilder(this.getSettingUrl, {"project_id" : this.projectSelected.value})).then(result => {
					this.showSettings = true;
					this.settings = result.data.data.results;
				}).catch(error => {
					this.settings = {};
					this.showSettings = false;
					this.showAlert("Error: " + error.response.data.message, "danger");
				});
            },
            saveSettings(){
				axios.post(this.settingUrl, {'settings': this.settings, 'project_id': this.projectSelected.value}).then(result => {
					this.showAlert('Settings Saved Successfully', 'success');
				}).catch(error => {
					this.showAlert('Error: ' + error.response.data.message, 'warning');
				});
            },
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown
			},
			showAlert(message,variant){
				this.dismissCountDown=this.dismissSecs
				this.statusMessage = message
				this.statusVar = variant
			},
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown
			},
		},
		mixins: [neuInputUtils,neuHandleProjectID],
		props: {
            settingUrl:{
            	type:String,
                required:true,
            },
            getSettingUrl:{
            	type:String,
                required:true
            }
		},
		components: {
            neuButton, bAlert
		},

	}
</script>
