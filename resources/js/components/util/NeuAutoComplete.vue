<template>
  <div>
    <span class="neu-search-field">
        <b-input-group>
            <input class="neu-input" :id="indexId" v-model="autoValue" type="text"
                   @input="autoSearch"/>
             <span class="neu-clear-search-icon" v-if="showClear" dusk="request-single-clear" @click="clearField">
                    <font-awesome-icon  :icon="clearIcom"></font-awesome-icon>
             </span>
         </b-input-group>
    </span>
    <ul tabindex="-1" v-if="resultsAutoLength"
        class="neu-autocomplete">
        <li tabindex="-1" v-for="(option, index) in resultsAuto" :key="index"
            class="cursor-pointer outline-none neu-autocomplete-list"
            @click.prevent="setOption(option)">
            {{ option }}
        </li>
    </ul>
  </div>
</template>
<style scoped>
    .neu-autocomplete{
        list-style:none;
        background-color:white;
        text-align:left;
        overflow:auto;
        width:100%;
        margin-top:1px;
        padding:0;
    }
    .neu-autocomplete-list{
        cursor:pointer;
        outline:0;
        border:solid gray;
        border-width:1px;
        padding-left:3px;
    }
</style>
<script>
	import axios from "axios";
	import neuInputUtils from "../mixins/neu-input-utils";
	import bInputGroup from "bootstrap-vue/es/components/input-group/input-group";
    import NeuButton from "./NeuButton";
    import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
    // add needed icons here
	import {faTimes} from "@fortawesome/free-solid-svg-icons";
	export default {
		data() {
			return {
				indexValues: {},
                resultsAuto: [],
                autoValue: '',
                typingTimer: null,
                clearIcom: faTimes,
			}
		},
        watch: {
		    clearAction(){
		    	if (this.clearAction){
		    		this.clearField();
                }
            }
        },
		mixins: [neuInputUtils],
		computed: {
			resultsAutoLength() {
				return this.resultsAuto.length > 0;
			}
		},
		methods: {
			autoSearch(){
                if (this.autoValue.length >= 3){
                	clearTimeout(this.typingTimer);
                	this.typingTimer = setTimeout(this.autoSearchApi, 1000);
                }
                else{
                	this.resultsAuto = [];
                }
                this.$emit('neu-auto-change', {key: this.autoKey, value: this.autoValue});
            },
            setOption(option){
                this.autoValue = option;
                this.resultsAuto = [];
				this.$emit('neu-auto-change', {key: this.autoKey, value: this.autoValue});
            },
            autoSearchApi(){
				let thisUrl = this.queryUrlBuilder(this.autoUrl, {"key" : this.autoKey, "value":this.autoValue});
				axios.get(thisUrl).then(result => {
					this.resultsAuto = result.data.data.results;
				}).catch(error => {
					this.$emit('neu-error', error.message);
				});
            },
            clearField(){
            	this.autoValue = '';
            	this.resultsAuto = '';
				this.$emit('neu-auto-change', {key: this.autoKey, value: this.autoValue});
            }
		},
		props: {
            indexId: {
            	type: String,
                required: false,
                default: 'auto-input',
            },
            autoKey: {
            	type: String,
                required: false,
                default: 'key',
            },
            autoUrl: {
            	type: String,
                required: true,
            },
            showClear: {
            	type: Boolean,
                required: false,
                default: true,
            },
            clearAction: {
                type: Boolean,
                required: false,
                default: false,
            }
		},
		components: {
            NeuButton, FontAwesomeIcon, bInputGroup
		
		}
	}
</script>
