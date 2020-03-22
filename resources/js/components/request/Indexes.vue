<template>
    <div class="col-12 neu-requestDiv-search clearfix">
             <div class="nue-requestDiv-content">
                    <template v-for="(indexChunk, i) in indexesChunk" >
                            <div class="neu-row" :key="i" >
                                <template v-for="(ind, key, index) in indexChunk">    
                                    <div :class="colClass" :key="index">
                                        <label class="neu-input-label" :for="'index_' + key">{{ind["label"]}}</label>
                                        <div >
                                            <span v-if="!autoOn" class="neu-search-field">
                                              <b-input-group>
                                                <input class="neu-input" :id="'index_' + key" v-model="indexValues[key]" type="text">
                                                <span style="top: 0px; z-index: 0" class="neu-clear-search-icon"  v-if="showClear" dusk="request-single-clear" @click="clearField(key)">
                                                    <font-awesome-icon  :icon="clearIcom"></font-awesome-icon>
                                                </span>
                                              </b-input-group>
                                            </span>
                                            <span v-else>
                                                <neu-auto-complete :auto-key="key"
                                                    :auto-url="autoUrl"
                                                    :index-id="'index_' + key"
                                                    :show-clear="showClear"
                                                    @neu-auto-change="changeValue"
                                                    :clear-action="clearActions[key]"></neu-auto-complete>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                    </template>
                    <div class="float-right">
                        <neu-button btn-type="action" btn-text="Clear All" btn-size="sm" @neu-btn-click="clear" btnDusk="request-clear"></neu-button>
                        <neu-button btn-type="confirm" btn-text="Search" btn-size="sm" @neu-btn-click="search" btnDusk="request-search"></neu-button>
                    </div>
             </div>
             
    </div>
</template>
<style scoped>

  
</style>

<script>
	import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
	import NeuAutoComplete from "../util/NeuAutoComplete";
	import axios from "axios";
	import NeuButton from "../util/NeuButton";
    import neuUtils from "../mixins/neu-utils";
    import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
    // add needed icons here
	import {faTimes} from "@fortawesome/free-solid-svg-icons"

    export default {
        data() {
            return {
                indexValues: {},
                errors: "",
                perPage: 25,
                firstPage: 1,
                inputType: "search",
                indexesChunk: [],
                showClear: true,
                indexes: {},
                clearActions: {},
                clearIcom: faTimes,
            }
        },
        mixins: [neuUtils],
        watch: {
        	indexesJson(){
				this.getIndexes();
            },
        },
        mounted() {
			this.getIndexes();
        },
        methods: {
        	getIndexes() {
				this.indexes = JSON.parse(this.indexesJson);
				let i = 0;
				Object.keys(this.indexes).forEach(c => {
					const numFloor = Math.floor(i / 4);
					if (i % 4 === 0) {
						Vue.set(this.indexesChunk, numFloor, {});
						Vue.set(this.indexesChunk[numFloor], c, this.indexes[c]);
					}
					else {
						Vue.set(this.indexesChunk[numFloor], c, this.indexes[c]);
					}
					i++;
                });
            },
        	search() {
				let values = {};
				for (let key in this.indexValues){
					if (this.indexValues[key] !== null && this.indexValues[key] != "")
						values[key] = this.indexValues[key];
				}
				this.$emit(this.filterEvent, {indexes:values});
            },
            clear() {
        	    for (let key in this.indexValues){
        	    	Vue.set(this.indexValues, key, "");
					if (this.autoOn){
						Vue.set(this.clearActions, key, true);
					}
                }
            },
            clearField(key) {
        		Vue.set(this.indexValues, key, "");
            },
			indexClass(index) {
				return (index % 2 === 0) ? "col-12" : "col-12";
			},
            changeValue(autoObj){
        		Vue.set(this.indexValues, autoObj.key, autoObj.value);
				Vue.set(this.clearActions, autoObj.key, false);
            },
        },
        props:{
			indexesJson: {
				type: String,
				required: true
			},
            filterUrl: {
				type: String,
                required: true
            },
            filterEvent: {
			    type:String,
                required:true,
            },
            autoOn: {
				type:Boolean,
                required:false,
                default:false,
            },
            autoUrl: {
				type:String,
                required:false,
                default:''
            },
            colClass: {
				type: String,
                required: false,
                default: 'col-12'
            }
		},
        components:{
            NeuButton, NeuAutoComplete, FontAwesomeIcon, bInputGroup
        }
    }
</script>
