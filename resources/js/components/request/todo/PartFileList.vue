<template>
	<div class="neu-filelist-table">

			<neu-table tabname="Upload"
					   :display-header="false"
					   :data-neu-table="fileListData"
					   :columns="columns"
					   :custom-add="true"
					   :create-new="false"
					   class="vm-margin"
                       :download-url="downloadUrl"
					   :all-paginate="true"
					   @neu-delete-file="openDeleteModal">

				<template slot="custom-add">
					<neu-button btn-size="lg" :btn-text="buttonText" dusk="neu-upload-btn" @neu-btn-click="openUploadModal"
								:id="buttonId"></neu-button>
				</template>

			</neu-table>
		<upload-file-form dusk="upload-file-form-comp"
						  @neu-files-uploaded="appendNewFiles"
						  :part-to-upload-for="partName"
						  :part-id="partId"
						  :box-to-upload-for="boxNumber"
						  :max-packet-size="packetSize"
						  :project-id="projectId"
						  :upload-uri="uploadUri"
						  :modal-title="'Upload files to box ' + boxNumber + ' with part '+ partName"
						  :modal-form-id="modalId"></upload-file-form>
        <b-modal dusk="modalDelete" v-model="modalDelete" title="Delete" @ok="removeSingleFile(fileToRemove, deletionReason, $event)">
            <p class="my-4">Please confirm the reason you are deleting this file.</p>
            <b-form-group horizontal
                    :label="'Reason: '"
                    label-text-align="left">
                <input dusk="deletionReason" v-model="deletionReason" class="neu-input neu-input-lg"/>
            </b-form-group>
            <b-alert class="col-md-10 offset-md-2" dusk="statusCRUD" :show="dismissCountDown" :variant="statusVar" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
                <p v-for="(message, $index) in statusMessage" :key="$index">{{ message }}</p>
            </b-alert>
        </b-modal>
	</div>
</template>

<script>

	import NeuTable from "../../util/NeuTable";
	import NeuButton from "../../util/NeuButton";
	import UploadFileForm from "../../forms/form-components/UploadFileForm";
	import neuAsyncUtil from "../../mixins/neu-async-util";
	import neuInputUtil from "../../mixins/neu-input-utils";
    import bModal from "bootstrap-vue/es/components/modal/modal";
    import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
    import bAlert from "bootstrap-vue/es/components/alert/alert";
	export default {
		data() {
			return {
                buttonText: "Upload to " + this.partName,
                fileListCopy: this.fileList,
                modalDelete: false,
                fileToRemove: "",
                deletionReason: "",
                dismissSecs: 5,
                dismissCountDown: 0,
                showDismissibleAlert: false,
                statusMessage: "",
                statusVar: ""
			};
		},
		mixins: [neuAsyncUtil, neuInputUtil],
		computed: {
			fileListData() {
				let fileListTemp = this.fileListCopy.map((fileList, index) => {
					return {
						// TODO: clean this up
						file_name: fileList.file_name,
						file_type: fileList.file_type,
						file_id: fileList.file_id,
						download: "box",
						file_actions_upload: {
							icon: "trash-alt",
							eventName: "neu-delete-file",
							eventPlaceHolderIcon: "cog",
							styles: "neu-delete-icon",
							isActing: false,
							rowId: this.boxNumber + "_" + this.partName + "_" + index
						}
					};
				});
				fileListTemp["total"] = this.fileListCopy.length;
				if (this.fileListCopy.hasOwnProperty("currentPage")) {
					fileListTemp["currentPage"] = this.fileListCopy["currentPage"];
				}
				else{
					fileListTemp["currentPage"] = 1;
				}
				return fileListTemp;
			},
			columns() {
				return [

					{key: "file_name", label: "File Name", visible: true},
					{key: "download", label: "Download", visible: true,  editable: false,"type":"TEXT"},
					{key: "file_actions_upload", label: "Actions", visible: true,
					formatter: (item) => { return item}}

				]
			},
			modalId() {
				return "neu-upload-form-" + this.partName + "-" + this.boxNumber;
			},
			buttonId() {
				return "neu-upload-btn-" + this.partName + "-" + this.boxNumber;
			}
		},
		methods: {
			appendNewFiles(fileArr) {
				fileArr.files.forEach(uploadedFiles => {
					const fileObj = uploadedFiles.file;
					const fileName = fileObj.name.split(".")[0];
					const fileType = fileObj.name.split(".").pop();
					this.fileListCopy.push({
						file_name: fileName,
						file_type: fileType,
						file_download_url: "https://localhost/" + fileName.concat(".", fileType),
						file_id:uploadedFiles.file_id
					});
					this.fileListCopy["currentPage"] = Math.ceil(this.fileListCopy.length / 25);

				});
			},
			openUploadModal() {
				this.$root.$emit("bv::show::modal", this.modalId)
			},
            openDeleteModal(file) {
                this.fileToRemove = file;
                this.modalDelete = true;
                this.$root.$emit("bv::show::modal", "modalDelete");
            },
			removeSingleFile(fileToRemove, deletionReason, event) {
                event.preventDefault();
                this.fileListData.forEach(async (fileInfo, index) => {
                    if (fileInfo.file_actions_upload.rowId === fileToRemove) {
                        Vue.set(fileInfo.file_actions_upload, "isActing", true);
                        axios.post(this.deleteUri, {
                            fileId: this.fileListCopy[index]['file_id'],
                            deletion_reason: deletionReason
                        }).then(({data}) => {
                            Vue.set(fileInfo.file_actions_upload, "isActing", true);
                            this.modalDelete = false;
                            Vue.delete(this.fileListCopy, index);
                            this.deletionReason = "";
                        }).catch(error => {
                            Vue.set(fileInfo.file_actions_upload, "isActing", false);
                            this.formatMessageForErrorAlert(error.response.data.errors);
                        });

						return true;
					}
				});
			},
            countDownChanged(dismissCountDown){
                this.dismissCountDown = dismissCountDown;
            },
            showAlert(message, variant){
                this.dismissCountDown = this.dismissSecs;
                this.statusMessage = message;
                this.statusVar = variant;
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

		props: {
			partName: {
				type: Number,
				required: true
			},
			fileList: {
				type: Array,
				required: true
			},
			boxNumber: {
				type: String,
				required: true
			},
			packetSize: {
				type: Number,
				required: false,
				default: 10
			},
			projectId: {
				type: String,
				required: true
			},
			uploadUri: {
				type: String,
				required: false,
				default: ""
			},
			deleteUri: {
				type: String,
				required: true
			},
			partId:{
				type:String,
				required:true
			},
            downloadUrl:{
                type: String,
                required: true,
            },
		},
		components: {
			NeuTable, NeuButton, UploadFileForm, bModal, bFormGroup, bAlert
		}
	}
</script>