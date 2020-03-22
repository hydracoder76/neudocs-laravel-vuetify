<template>
    <div class="neu-upload-file-form">
        <neu-form dusk="upload-file-form"
                  :is-modal="true"
                  @neu-reset-form-fields="resetFiles"
                  @neu-submit="uploadFiles"
                  modal-size="lg"
                  :neu-form-modal-id="modalFormId"
                  ok-title-modal="Upload">
            <template dusk="upload-file-modal-title" slot="neu-form-modal-title">
                {{ modalTitle }}
            </template>

            <template dusk="upload-file-modal-content" slot="neu-modal-form-content">
                <div dusk="neu-upload-form">
                    <b-form-group>
                        <p class="neu-footnote">Note: The upload limit is {{ humanFriendlyFileSize }}</p>
                        <b-alert dusk="upload-error" :show="dismissCountDown" variant="danger" dismissible fade @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
                            <p>{{ message }}</p>
                        </b-alert>
                        <b-form-file dusk="neu-upload-file"
                                     aria-hiden="true"
                                     tabindex="0" plain
                                     select-format="Drag & Drop Files Here"
                                     id="upload-files"
                                     drop-label="Drag & Drop Files Here"
                                     v-model="filesToUpload" multiple
                                     placeholder="Choose a file to upload"
                                     ref="files"></b-form-file>
                    </b-form-group>

                    <b-form-group label="Files to be uploaded" v-if="filesToUpload.length > 0">
                        <neu-table :create-new="false"
                                   class="vm-margin"
                                   :display-header="false"
                                   :custom-add="true"
                                   :columns="fileCols"
                                   :data-neu-table="fileRows"
                                   :all-paginate="true"
                                   ref="status"
                                   @neu-delete-file="removeSingleFile">
                            <template slot="custom-add">

                            </template>
                        </neu-table>
                    </b-form-group>


                </div>


            </template>

        </neu-form>
        <neu-shadow-backdrop :show="isUploading"
                             :shadow-text="shadowText"
                             :shadow-text-color="shadowTextColor"></neu-shadow-backdrop>
    </div>
</template>
<style lang="scss" scoped>
    // TODO: tweak the file input to be a little more in line with the app
    .neu-modal-form .custom-file-control {
        position: absolute;
    }

    .neu-modal-form .custom-file-control::before {
        bottom: 5px;
        left: 30%;
        right: 30%;
        top: 200px;
        padding-left: 10%;
        color: white;
        background-color: #595959;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        display: inline-block;
    }

    .neu-modal-form .custom-file-control::after {
        color: #595959;
        font-size: 2rem;
        left: 28%;
        right: 25%;
        top: 100px;
        position: absolute;
    }
</style>
<script>

	import NeuForm from "../NeuForm";
	import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
	import bFormInput from "bootstrap-vue/es/components/form-input/form-input";
	import bFormFile from "bootstrap-vue/es/components/form-file/form-file";
	import bAlert from "bootstrap-vue/es/components/alert/alert";
	import NeuTable from "./../../util/NeuTable";
	import neuAsyncUtil from "./../../mixins/neu-async-util";
	import NeuShadowBackdrop from "../../util/NeuShadowBackdrop";
	import moment from "moment";
	import {UPLOAD_CONST} from "../../../classes/neu-constants";
	import axios from "axios";

	export default {
		data() {
			return {

				// TODO: wrap the JS file object to extend it in to the PartFile object, not urgent
				filesToUpload: [],
				isUploading: false,
				shadowText: "Uploading...",
				shadowTextColor: "#ffffff",
				filePackets: null,
				hasError: false,
                noFilesOverLimit: true,
				dismissSecs: 5,
				dismissCountDown: 0,
				showDismissibleAlert: false,
                message: ""
			};
		},
		methods: {
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown;
			},
			buildFileUploadPacket(fileData) {
				const formData = new FormData();
                formData.append("file_meta_data", JSON.stringify({

					file_name: fileData.name,
					file_mime: fileData.type,
					time_of_upload: moment().date(fileData.lastModifiedDate).toLocaleString(),
					upload_type: UPLOAD_CONST.FORM_UPLOAD,
					box_number: this.boxToUploadFor,
					part_name: this.partToUploadFor,
                    part_id: this.partId

				}));
				formData.append("file_data", fileData);
				formData.append("project_id", this.projectId);
				return formData;


			},
			countDownChanged(dismissCountDown){
				this.dismissCountDown = dismissCountDown;
			},
			stagePackets(packetArr) {
				// TODO: this will change once sync is added
				return packetArr.map(file => {
					return axios.post(this.uploadUri, this.buildFileUploadPacket(file)).catch(error => {
						this.hasError = true;
						this.isUploading = false;
					});

				});

				// loop through each file to upload and make a packet, callin upload for each

			},
			async uploadFiles() {
				this.isUploading = true;
				let chunkArr = [];
				for (let i = 0; i < this.filesToUpload.length; i += this.maxPacketSize) {
					chunkArr.push(this.filesToUpload.slice(i, i + this.maxPacketSize));
				}

				for (let i in chunkArr) {
					this.filePackets = this.stagePackets(chunkArr[i]);

					// should upload with no cap, needs cap
					await axios.all(this.filePackets).then(result => {
                        const uploadedFiles = [];
                        for (let datum in chunkArr[i]) {
                        	uploadedFiles.push({
                                file: chunkArr[i][datum],
                                file_id: result[datum].data.data.file_id
                            })
                        }
						const eventPayload = {
							files: uploadedFiles,
							boxNumber: this.boxToUploadFor,
							partName: this.partName
						};
						this.$emit("neu-files-uploaded", eventPayload);
						this.$root.$emit("bv::hide::modal", this.modalFormId);
					}).catch(error => {
						this.dismissCountDown = this.dismissSecs;
						this.message = "An error occurred during upload, please try again.";
                    });
				}


				this.isUploading = false;
				this.resetFiles();

			},
			resetFiles() {
				Vue.set(this.filesToUpload, []);
				this.$refs.files.reset();
				this.noFilesOverLimit = true;
				this.message = "";
			},
			removeSingleFile(fileToRemove) {
				this.fileRows.forEach((fileInfo, index) => {
					if (fileInfo.file_actions_upload.rowId === fileToRemove) {

						Vue.delete(this.filesToUpload, index);
						this.filesToUpload["currentPage"] = Math.ceil(this.filesToUpload.length / 25);
						if (this.filesToUpload.length === 0) {
							this.resetFiles();
						}
						return true;
					}
				});
			},
			isFileOverLimit(fileSize) {
				return fileSize >= this.maxUploadSize;
            }
		},
		mixins: [neuAsyncUtil],
		computed: {
			humanFriendlyFileSize() {
				return Math.ceil(this.maxUploadSize / 1028 / 1028 / 1028) + " gigabytes";
            },
			// TODO: clean this up
			fileRows() {
				const fileUploadCopy = this.filesToUpload;
				const fileRowTemp = fileUploadCopy.filter((fileObj, index) => {
					if (this.isFileOverLimit(fileObj.size)) {
						this.dismissCountDown = this.dismissSecs;
						this.message = "The file " + fileObj.name + " is over the maximum allowed file size and was not added.";
						return false;
					}
					return true;
                }).map((fileObj, index) => {

						return {
							fileName: fileObj.name,
							fileSize: Math.floor(fileObj.size / 1024) + "kb",
							file_actions_upload: {
								icon: "trash-alt",
								eventName: "neu-delete-file",
								isActing: false,
								eventPlaceHolderIcon: "cog",
								styles: "neu-delete-icon",
								rowId: this.boxToUploadFor + "_" + this.partToUploadFor + "_" + index
							}
						};
				});
				fileRowTemp['total'] = this.filesToUpload.length;
				if (this.filesToUpload.hasOwnProperty("currentPage")) {
					fileRowTemp["currentPage"] = this.filesToUpload["currentPage"];
				}
				else{
					fileRowTemp["currentPage"] = 1;
				}
				return fileRowTemp;
			},
			fileCols() {
				return [{key: "fileName", label: "File Name", visible: true},
					{key: "fileSize", label: "File Size", visible: true},
					{
						key: "file_actions_upload", label: "File Actions", visible: true,
						// needed so that value is set correctly
						formatter: (item) => {
							return item;
						}
					}
				];
			}
		},
		props: {
			boxToUploadFor: {
				type: String,
				required: true
			},
			partToUploadFor: {
				type: Number,
				required: true
			},
			modalFormId: {
				type: String,
				required: false,
				default: ""
			},
			modalTitle: {
				type: String,
				required: false,
				default: "Upload to parts"
			},
			maxPacketSize: {
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
				required: true
			},
			partId:{
				type:String,
				required:true
			},
            maxUploadSize: {
				type: Number,
                required: false,
                default: 5368709120
            }
		},
		components: {
			NeuForm,
			bFormInput,
			bFormGroup,
			bFormFile,
            bAlert,
			NeuTable,
			NeuShadowBackdrop
		}
	}
</script>
