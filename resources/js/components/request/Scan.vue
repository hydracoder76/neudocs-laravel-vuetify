<template>
    <div class="container">
        <neu-button :btn-text="showScan ? 'Hide Scan Controls' : 'Start Scanning'" @neu-btn-click="showScanControls" btn-size="lg"></neu-button>
        <hr>
        <div v-show="showScan">
        <div id="scanScroll" class="row">
            <div class="col-md-4">
                <div>
                    <label for="scanSelect" class="scanLabel" id="firstScanLabel">Select Scanner</label>
                    <span><select id="scanSelect" class="form-control" v-on:change="loadNewScanner" v-model="cur_scan" :disabled="disableScanSelect">
                        <option v-for="(scanner, key, index) in viable_scanners" :value="key">{{key}}</option>
                    </select></span>
                    <div v-if="showSettings"><input type="checkbox" v-model="settingOn"> Show Settings</div>
                    <neu-button id="scanButton" class="bot_scan" @neu-btn-click="scan" btn-text="Scan"></neu-button>
                </div>
                <div>
                    <label for="scanPageJump" class="scanLabel" id="jumpScanLabel">Current Page</label>
                    <label v-if="twainMaxPage > 0" class="scanLabel" id="numScanLabel">{{currentPage}}/{{twainMaxPage + 1}}</label>
                    <div id="scanPageNum">
                        <span><neu-button btn-size="sm" @neu-btn-click="pageLeft()"  btn-text="&lt;"></neu-button>
                        <input id="scanPageJump" type="text" v-on:keyup.enter="pageJump" size="10" v-model="pageNav">
                        <neu-button btn-size="sm" @neu-btn-click="pageRight()"  btn-text="&gt;"></neu-button></span>
                    </div><br>
                    <label for="scanRotate">Page Controls</label>
                    <div class="row" id="scanRotate">
                        <div class="col-xs-4">
                            <neu-button btn-text="Rotate Left" @neu-btn-click="rotateLeft()"></neu-button>
                        </div>
                        <div class="col-xs-2"></div>
                        <div class="col-xs-4">
                            <neu-button btn-text="Rotate Right" @neu-btn-click="rotateRight()"></neu-button>
                        </div>
                    </div>
                    <div>
                        <neu-button btn-text="Delete Selected Image" @neu-btn-click="deletePage"></neu-button>
                        <neu-button btn-text="Clear Scanned Images" @neu-btn-click="clearPages"></neu-button>
                    </div><br>
                        <neu-button btn-text="Options" @neu-btn-click="openModal(currentPage)"></neu-button>
                </div>
            </div>
            <div class="col-md-2" id="middleCol"></div>
            <div class="col-xs-6 cleafix p-0" @mouseover="scanMouseOver = true" @mouseout="scanMouseOver = false" id="scanContainer">
                <div id="dwtcontrolContainer"></div>
            </div>
        </div>
        </div>
        <div id="scanNav">
            <div class="box-block">
                <div v-for="part in parts" v-if="part['part_id']">
                    <strong   class="box-text pb-4" >{{part.box}}-{{part.part_name}} :</strong>
                </div>
            </div>
            <template v-for="part in parts" v-if="part['part_id']">
                <div>
                    <template>
                        <template v-for="item in dataAddChecked[part['part_id']]" v-if="dataAddChecked[part['part_id']].length > 1">
                            <b-form-checkbox v-if="item === option.id"
                                             v-for="option in checkboxOptions"
                                             v-model = "mediaTypeEmpty[part['part_id']]"
                                             :key="option.id"
                                             :value="option.id"
                                             name="flavour-3a"
                            >
                                {{ option.type }}
                            </b-form-checkbox>
                        </template>

                    </template>
                </div>
            </template>

            <neu-button btn-text="Cancel" btn-type="cancel" @neu-btn-click="goBack" id="cancel-btn"></neu-button>
            <i v-if="uploading" class="fa fa-spin fa-spinner"></i>
            <neu-button btn-text="Upload" btn-type="action" id="scan-upload-btn" @neu-btn-click="upload"></neu-button>
        </div>
        <b-modal title="Scan Options" ref="scanSelectModal" @ok="saveInfo">
            <b-form-checkbox dusk="deleted-check" id="deleted-check" v-model="deletedCheck" :disabled="disableDelete">Mark as Deleted<font-awesome-icon id="deleted-help" :icon="getFaIcon" /></b-form-checkbox>
            <b-tooltip target="deleted-help" placement="right">Mark as deleted before uploading</b-tooltip><br>
            <span>First Page Of<font-awesome-icon id="first-help" :icon="getFaIcon" /></span>
            <b-tooltip target="first-help" placement="right">Move scanned file to first page of part</b-tooltip><br>
            <b-form-select dusk="part-select" id="part-select" :options="parts" v-model="selectedPart" :disabled="deletedCheck"></b-form-select>
        </b-modal>
        <b-modal title="Success" ref="uploadSuccessModal" @ok="goBack" :ok-only="true">
            Upload Successful
        </b-modal>
        <b-modal title="Error" ref="errorModal" :ok-only="true">
            {{errorMessage}}
        </b-modal>
        <div v-if="uploading" class="neu-scan-backdrop" style="z-index: 500003;"></div>
    </div>
</template>
<style>
    #scanScroll{
        max-height:700px;
    }
    #middleCol{
        max-width:5%;
    }
    #scanPageJump{
        height:26px;
        width:100px;
    }
    #dwtcontrolContainer{
        width:500px;
        height:100%;
    }
    #scanSelect{
        margin-bottom:0.5rem;
    }
    #jumpScanLabel{
        margin-top:10px;
    }
    .custom-control-label{
        top:-15px !important;
    }
    body > div:nth-of-type(3){
        display:none !important;
    }
    .dynamsoft-backdrop{
        display:none !important;
    }
    .neu-scan-backdrop{
        position: fixed;
        background: rgba(0,0,0,0.1);
        top: 0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
    }

    .box-block{
        float: left;
        margin-top: -17px;
        margin-right: 12px;
    }

</style>
<script>
	import neuButton from "../util/NeuButton";
	import bModal from "bootstrap-vue/es/components/modal/modal";
	import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
	import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
	import bProgress from "bootstrap-vue/es/components/progress/progress";
	import bTooltip from "bootstrap-vue/es/components/tooltip/tooltip";
	import bButton from "bootstrap-vue/es/components/button/button";
	import {UPLOAD_CONST} from "../../classes/neu-constants";
	import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
	import neuMediaType from "./../mixins/neu-media-type";

	// add needed icons here
	import { faInfoCircle  } from "@fortawesome/free-solid-svg-icons";
    import axios from "axios";
	export default {
		data(){
			return {
                parts:[],
                currentKey:0,
                imageData:[],
				selectedPart:'',
                deletedCheck:false,
                firstCheck:false,
                uploading:false,
				DWObject:{},
				viable_scanners: {},
				twainMaxPage: 0,
				pageNumString: '',
				uploadSpinner: false,
				currentPage: 0,
				cur_scan:'',
				uploadCheck: false,
				disableScanSelect:false,
				scannerIndex:0,
				showSettings:false,
				settingOn:false,
				showSettingsCtrl:false,
				scanMouseOver: false,
				pageNav: '',
                showScan:false,
                loadedTwain: false,
                loadedScan: false,
                errorMessage:'',
                checkboxOptions:[],
                dataAddChecked:[],
                mediaTypeEmpty:[],
			}
		},
        created(){
			let scanScript = document.createElement('script');
			scanScript.setAttribute('src', "/vendor/DynamicWebTWAINv13.2/Resources/dynamsoft.webtwain.initiate.js");
			let configScript = document.createElement('script');
			configScript.setAttribute('src', "/vendor/DynamicWebTWAINv13.2/Resources/dynamsoft.webtwain.config.js");
			document.head.appendChild(scanScript);
			document.head.appendChild(configScript);
			let interval = setInterval(function () {
				if (document.getElementsByClassName('dynamsoft-dwt-dialogProgress').item(0) != null) {
					document.getElementsByClassName('dynamsoft-dwt-dialogProgress').item(0).style.display='none';
					clearInterval(interval);
				}
				this.loadedTwain = true;
			}, 100);
			this.loadedTwain = true;
        },
		mounted() {
			let self = this;
			document.addEventListener("keyup", function(e){
				if (e.ctrlKey && e.keyCode === 55)
					self.showSettingsCtrl = true;
				if (self.showSettingsCtrl && !self.showSettings && e.keyCode == 83)
					self.showSettings = true;
			});
			if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
				window.addEventListener('wheel', function (e) {
					if (self.scanMouseOver) {
						e.preventDefault();
						if (e.deltaY > 0)
							self.pageRight();
						else
							self.pageLeft();
						return false;
					}
				});
			}

            const partsStorage = localStorage.getItem('todo_scan_parts');
			if (partsStorage != null) {
				this.parts = JSON.parse(partsStorage);
				this.parts.unshift({'value': '', 'text': '', 'disabled': false})
			}

            this.getPartMediaTypes(this.partMediaTypeLoad, this.parts).then(values => {
                this.dataAddChecked = values[0];
                this.mediaTypeEmpty = values[1];
            });
            this.getMediaTypes();
		},
        computed:{
			getFaIcon(){
			    return faInfoCircle;
            },
            disableDelete(){
            	return this.selectedPart != '' && this.selectedPart != null;
            }
        },
		methods: {
        	showScanControls(){
        		if (!this.loadedTwain)
        			return;
        	    this.showScan = !this.showScan;
        	    if (!this.loadedScan)
        	    	this.loadScan();
            },
            upload(){
                this.uploading = true;
                let self = this;
                setTimeout(function(){
                	self.DWObject.CloseSource();
                	self.refreshScanner();
					let pages = self.gatherPages();
					if (pages === null) {
						self.uploading = false;
						return;
					}
					self.DWObject.HttpFieldNameOfUploadedImage = 'file_data';
					self.uploadApiCall(0, pages);
                }, 1000);
            },
            uploadApiCall(page, pages){
            	let self = this;
				this.DWObject.ClearAllHTTPFormField();
				const fileName = pages[page]['part_name'] + '_' + Date.now() + ".pdf";
				let metaData = JSON.stringify({
					file_name: fileName,
					file_mime: 'pdf',
					time_of_upload: '',
					upload_type: UPLOAD_CONST.FORM_UPLOAD,
					box_number: pages[page]['box'],
					part_name: pages[page]['part_name'],
					part_id: pages[page]['part'],
                    is_scanned: true
				});
				this.DWObject.SetHTTPFormField('file_meta_data', metaData);
				this.DWObject.SetHTTPFormField('project_id', pages[page]['project_id']);
				this.DWObject.HTTPUpload(this.uploadApi, pages[page]['pages'], 4, 0, '', function(){
					if (page === pages.length - 1){
						self.uploading = false;
						self.$refs.uploadSuccessModal.show();
					}
					else{
						self.uploadApiCall(page + 1, pages);
                    }
				}, this.onUploadFailure)
            },
            onUploadFailure(){
				this.uploading = false;
				const startIndex = this.DWObject.HTTPPostResponseString.indexOf('"message":') + 10;
				const error = this.DWObject.HTTPPostResponseString.substring(startIndex).replace(/"/g, '').replace(/}/g, '');
            },
            gatherPages(){
            	let pages = [];
            	let curKey = -1;
            	let firstPage = false;
            	if (this.imageData.length === 0){
					this.errorMessage = "No pages scanned";
					this.$refs.errorModal.show();
					return null;
                }
            	for(let page in this.imageData){
            		const part = this.imageData[page]['part'];
            		const notDeleted = (typeof this.imageData[page]['delete'] === 'undefined' || !this.imageData[page]['delete']);
            		if (!firstPage && (part == null || part == '') && notDeleted){
            			this.errorMessage = "First nondeleted scanned page needs to be marked as going to a part";
                        this.$refs.errorModal.show();
            			return null;
                    }
                    if (part !== null && part !== ''){
						for(let tempPart in this.parts){
							if (this.parts[tempPart]['value'] === part) {
								pages.push({part:part, pages:[], box:this.parts[tempPart]['box'],
                                    project_id:this.parts[tempPart]['project_id'], part_name:this.parts[tempPart]['part_name'],
                                    uploaded:false});
								curKey++;
								firstPage = true;
								break;
							}
						}
                    }
                    if (curKey !== -1 && notDeleted)
                    	pages[curKey]['pages'].push(page);
                }
                return pages;
            },
            goBack(){
                this.parts.shift();
				axios.post(this.unlockApi, {parts:this.parts, dataAddChecked: this.mediaTypeEmpty}).then(result => {
					location.href = this.prevUrl;
				}).catch(error => {

				});

            },
            openModal(key){
            	if (typeof key != 'number' || key == 0)
            		return;
            	key = key - 1;
            	this.selectedPart = this.imageData[key]['part'];
            	this.deletedCheck = this.imageData[key]['delete'];
            	this.firstCheck = this.imageData[key]['first'];
            	this.$refs.scanSelectModal.show();
            },
            saveInfo(){
            	const key = this.currentPage - 1;
            	this.setPart(this.imageData[key]['part'], false);
            	this.imageData[key] = {};
                this.imageData[key]['part'] = this.selectedPart;
				this.imageData[key]['delete'] = this.deletedCheck;
				if (this.selectedPart != '') {
					this.setPart(this.selectedPart, true);
				}
                else {
					this.setPart(this.selectedPart, false);
				}

            },
            setPart(key, option){
            	for(let part in this.parts){
                    if (this.parts[part]['value'] == key)
                    	Vue.set(this.parts[part], 'disabled', option);
                }
            },
			loadScan(){
				this.DWObject = Dynamsoft.WebTwainEnv.GetWebTwain('dwtcontrolContainer');
				this.DWObject.RegisterEvent('OnTopImageInTheViewChanged', this.scanned);
				this.DWObject.ProductKey = this.dwtProductKey;
				this.DWObject.SetViewMode(1,1);
				this.loadScanners();
				if (window.location.protocol == 'https:') {
					this.DWObject.HTTPPort = 443;
					this.DWObject.IfSSL = true;
				}
				this.DWObject.IfShowIndicator = false;
				this.DWObject.Height = '600px';
				this.DWObject.Width = '500px';
				this.loadScan = true;
			},
			scanned(sImageIndex){
				if(sImageIndex > this.twainMaxPage)
					this.twainMaxPage = sImageIndex;
				this.DWObject.CurrentImageIndexInBuffer = sImageIndex;
				this.currentPage = sImageIndex + 1;
				for (let i = this.imageData.length; i < this.twainMaxPage + 1; i++){
					this.imageData.push({});
                }
			},
			DisplayDownloadDynamicWebTwainModal() {
				let DWPath = Dynamsoft.Lib.product.bChromeEdition ? Dynamsoft.Lib.product.getChromeEditionPath() : Dynamsoft.Lib.product.getMSIPath();
				OnWebTwainNotFoundOnWindowsCallback(
					Dynamsoft.Lib.product.name,
					DWPath,
					Dynamsoft.Lib.product.bChromeEdition,
					Dynamsoft.Lib.env.bIE,
					Dynamsoft.Lib.env.bSafari,
					Dynamsoft.Lib.detect.ssl,
					Dynamsoft.Lib.env.strIEVersion
				);
			},
			loadScanners(){
				this.disableScanSelect = false;
				let avail_scanners = [];
				for (let i = 0; i < this.DWObject.SourceCount; i++)
					avail_scanners.push(this.DWObject.SourceNameItems(i));
				let is_viable_scanner = false;
				let default_scanner = '';
				if (avail_scanners.length > 0){
					is_viable_scanner = true;
					default_scanner = avail_scanners[0]
				}
				for (let i = 0; i < avail_scanners.length; i++) {
					Vue.set(this.viable_scanners, avail_scanners[i], i);
				}
				if (is_viable_scanner) {
					this.loadScanner(this.viable_scanners[default_scanner]);
					this.cur_scan = default_scanner;
				}
			},
            loadScanner(scanner){
				this.scannerIndex = this.viable_scanners[scanner];
				this.DWObject.CloseSource();
				this.DWObject.SelectSourceByIndex(this.scannerIndex);
				this.DWObject.OpenSource();
				this.DWObject.Resolution = 300;
				this.DWObject.IfDuplexEnabled = true;
				this.DWObject.IfFeederEnabled = true;
				this.DWObject.IfAutomaticDeskew = true;
				this.DWObject.IfAutomaticBorderDetection = true;
				this.DWObject.Capability = 0x1024;
            },
			loadNewScanner(e){
            	this.loadScanner(e.target.value);
			},
			refreshScanner(){
				this.loadScanner(this.scannerIndex);
			},
			scan(){
				if (this.DWObject.IfPaperDetectable && !this.DWObject.IfFeederLoaded) {
					alert('No pages detected in feeder');
					return false;
				}
				this.DWObject.IfShowUI = false;
				this.DWObject.IfScanInNewThread = true;
				if (this.settingOn){
					this.DWObject.IfShowUI = true;
					this.DWObject.IfScanInNewThread = false;
				} else {
					this.DWObject.IfShowUI = false;
					this.DWObject.IfScanInNewThread = true;
				}
				let res = this.DWObject.AcquireImage();
				if (!res) {
					alert(this.DWObject.ErrorString);
					return false;
				}
				this.disableScanSelect = true;
			},
			rotateRight(){
				this.DWObject.RotateRight(this.DWObject.CurrentImageIndexInBuffer);
			},
			rotateLeft(){
				this.DWObject.RotateLeft(this.DWObject.CurrentImageIndexInBuffer);
			},
			deletePage(){
				if (this.DWObject.HowManyImagesInBuffer <= 0)
					return false;
				let key = this.DWObject.CurrentImageIndexInBuffer;
				this.imageData.splice(key, 1);
				this.DWObject.RemoveImage(this.DWObject.CurrentImageIndexInBuffer);
				this.twainMaxPage -= 1;
				this.currentPage = this.DWObject.CurrentImageIndexInBuffer + 1;
			},
			pageLeft(){
				let index = this.DWObject.CurrentImageIndexInBuffer;
				if (index > 0) {
					this.DWObject.CurrentImageIndexInBuffer = index - 1;
					this.currentPage = index;
				}
			},
			pageRight(){
				let index = this.DWObject.CurrentImageIndexInBuffer,
					maxIndex = this.DWObject.HowManyImagesInBuffer - 1;
				if (index < maxIndex) {
					this.DWObject.CurrentImageIndexInBuffer = index + 1;
					this.currentPage = index + 2;
				}

			},
			pageJump(e){
				let indexTarget = parseInt(e.target.value);
				let maxIndex = this.DWObject.HowManyImagesInBuffer;
				if (indexTarget > 0 && indexTarget <= maxIndex) {
					this.DWObject.CurrentImageIndexInBuffer = indexTarget - 1;
					this.currentPage = indexTarget;
				}
			},
			clearPages(){
				this.DWObject.RemoveAllImages();
				this.twainMaxPage = 0;
				this.currentPage = 0;
				this.imageData = [];
			},            
            getMediaTypes(){
                axios.get(this.mediaTypeLoad).then(mediaTypeResult => {
                    this.checkboxOptions = mediaTypeResult.data.data[0];
                });
            },
		},
		mixins: [neuMediaType],
		props: {
            prevUrl:{
            	type:String,
                required:true
            },
            dwtProductKey:{
            	type:String,
                required:true
            },
            uploadApi:{
            	type:String,
                required:true
            },
            partsJson:{
            	type:String,
                required:false,
                default:''
            },
            unlockApi:{
            	type:String,
                required:true,
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
		components:{
			neuButton, bModal, bFormCheckbox, bFormSelect, bProgress, bTooltip, bButton, FontAwesomeIcon
		}
	}
</script>
