<template>
    <div>
        <template v-for="(part, key) in parts">
            <div>
                <upload dusk="neu-upload-component">
                    <h2 slot="list-header">
                        Part: {{part['part_name']}}
                    </h2>
                    <div class="neu-bump-d-md"></div>

                    <box-part-list slot="box-part-list" :box-number="part['box_name']"
                                   :requests="part['requests']" dusk="neu-box-part-list-component">
                        <part-file-list slot="part-file-list"
                                        :part-name="part['part_name']"
                                        :part-id="part['part_id']"
                                        :box-number="part['box_name']"
                                        :packet-size="packetSize"
                                        :upload-uri="uploadApi"
                                        :delete-uri="deleteApi"
                                        :download-url="downloadUrl"
                                        :file-list="part['files']" dusk="neu-part-file-list-component"
                                        :project-id="projectId"></part-file-list>
                    </box-part-list>


                </upload>
            </div>
            <div style="text-align: right">
                <template v-for="item in dataAddChecked[part['part_id']]" v-if="dataAddChecked[part['part_id']].length > 1">
                    <b-form-checkbox v-if="item == option.id"
                                     v-for="option in checkboxOptions"
                                     v-model = "mediaTypeEmpty[part['part_id']]"
                                     :key="option.id"
                                     :value="option.id"
                                     name="flavour-3a">
                        {{ option.type }}
                    </b-form-checkbox>
                </template>
            </div>
        </template>

        <div class="neu-btn-cont">
            <div class="neu-btn-cont-right">
                <neu-button btn-text="Done" @neu-btn-click="goBack"></neu-button>
            </div>
        </div>
    </div>
</template>

<script>
	import neuButton from "../../util/NeuButton";
	import PartFileList from "./PartFileList";
	import BoxPartList from "./BoxPartList";
	import Upload from "./Upload";
    import axios from "axios";
    import bFormCheckbox from "bootstrap-vue/es/components/form-checkbox/form-checkbox";
    import neuMediaType from "./../../mixins/neu-media-type";

	export default {
		data() {
			return {
                parts:[],
                requestParts:[],
                fileString:'',
                checkboxOptions:[],
                dataAddChecked:[],
                mediaTypeEmpty:[]
			};
		},
        created() {            
            this.parts = JSON.parse(this.partsJson);
            this.requestParts = JSON.parse(this.requestPartsJson);
            axios.get(this.mediaTypeLoad).then(mediaTypeResult => {
                this.checkboxOptions = mediaTypeResult.data.data[0];
            });
            this.getPartMediaTypes(this.partMediaTypeLoad, this.requestParts).then(values => {
                this.dataAddChecked = values[0];
                this.mediaTypeEmpty = values[1];
            });            
        },

        methods:{
			goBack(){
				axios.post(this.unlockApi, {parts: this.parts, dataAddChecked: this.mediaTypeEmpty}).then(result => {
					location.href = this.prevUrl;
				}).catch(error => {
                    console.log(error);
				});
			}
        },
        mixins: [neuMediaType],
		props: {
            unlockApi:{
            	type:String,
                required:true,
			},
			partsJson:{
				type:String,
				required:true,
			},
            requestPartsJson:{
            	type:String,
                required:true,
            },
			prevUrl:{
				type:String,
				required:true
			},
			uploadApi:{
				type:String,
				required:true
			},
            deleteApi:{
            	type:String,
                required:true,
            },
            packetSize:{
            	type:Number,
                required:true,
            },
            projectId:{
            	type:String,
                required:true,
            },
            downloadUrl:{
                type: String,
                required: true,
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
		components: {
            neuButton, PartFileList, BoxPartList, Upload, bFormCheckbox
		}
	}
</script>
