<template>
     <div class="col-12">
        <div class="panel-heading" dusk="panel-header">Activity Log</div>
		 <span v-show="waitingIndicator === false">
        <div class="neu-row col-md-12 pb-4">
			<div class="col-md-3 pl-0">
 				<datepicker placeholder="Date from" :calendar-button="true" calendar-button-icon="fa fa-calendar-alt" id="dateFrom" 
				 :typeable="true" v-model="dateFrom" ref="dateFrom">
				 </datepicker>
			</div>
			<div class="col-md-3">
 				<datepicker placeholder="Date to" id="dateTo" :calendar-button="true" calendar-button-icon="fa fa-calendar-alt"
				 :typeable="true" v-model="dateTo" ref="dateTo">
				 </datepicker>
			</div>    
			<div class="col-md-6">
				<neu-button btn-type="action" btn-size="sm" btn-text="Clear All" @neu-btn-click="clear" btn-dusk="log-clear"></neu-button>
				<neu-button btn-type="confirm" btn-size="sm" btn-text="Search" @neu-btn-click="dateSearch" btn-dusk="request-search"></neu-button>
			</div>
        </div>
        
        <neu-table title=""
                   ref="status"
                   :data-neu-table="dataTable"
                   :columns="dataColumns"
                   :no-local-sorting="true"
                   :display-header="false"
                   :create-new="false"
                   @page-change="logSearch"
                   @neu-sort-changed="logSort"
                   class="vm-margin p-0"
        >
        </neu-table>
			 </span>
		 <neu-shadow-backdrop :show="waitingIndicator" :shadow-text="waitingText"
							  shadow-icon="cog"></neu-shadow-backdrop>
    </div>
</template>
<style scoped>
    .label{
		font-size: 80%;
		margin-bottom: 0;
		font-weight: 500;
	}
</style>

<script>
	import NeuTable from "../util/NeuTable";
	import NeuButton from "../util/NeuButton";
	import axios from "axios";
	import neuInputUtils from "../mixins/neu-input-utils";
	import Datepicker from "vuejs-datepicker";
	import moment from "moment";
	import NeuShadowBackdrop from "../util/NeuShadowBackdrop";


	export default {
		props: {
			logApi: {
				type: String,
                required: true
            }
		},
		mixins: [neuInputUtils],
		components: {
			NeuTable, Datepicker, NeuButton, NeuShadowBackdrop
		},
		mounted() {
			this.logSearch(1);
		},
		methods: {
			logSort(sortObj) {
				if (sortObj.hasOwnProperty("sortBy") && sortObj.sortBy !== null) {
					this.sortBy = sortObj.sortBy;
					this.sortDesc = sortObj.sortDesc ? "desc" : "asc";
					this.logSearch(1);
				}
			},
			dateSearch() {
				this.sortBy = "time";
				this.sortDesc = "desc";
				this.logSearch(1);
			},
			clear() {
				this.sortBy = "time";
				this.dateFrom = "";
				this.dateTo = "";
				this.sortDesc = "desc";
				this.logSearch(1);
			},
			logSearch (page) {
				this.waitingIndicator = true;
				const dateFrom = this.dateFrom === "" ? "" : moment(this.dateFrom).format("YYYY-MM-DD 00:00:00");
				const dateTo = this.dateTo === "" ? "" : moment(this.dateTo).format("YYYY-MM-DD 23:59:59");
				axios.get(this.queryUrlBuilder(this.logApi, {page : page, dateFrom: dateFrom,
                    dateTo: dateTo, order : this.sortDesc, sortBy: this.sortBy})).then(result => {
                    	this.waitingIndicator = false;
					this.dataTable = result.data.data.results.results;
					this.dataTable["total"] = result.data.data.results.total;
					this.dataTable["currentPage"] = page;
				}).catch(error => {
					this.waitingIndicator = false;
					this.$refs.status.formatMessageForErrorAlert(error.response.data.errors);
				});
			}

		},
		data() {
			return {
				dataTable:[],
				dataColumns: [
					{key: "user", label: "User", sortable: true, visible: true, "type":"TEXT" },
					{key: "company", label: "Company", sortable: true, visible: true, "type":"TEXT" },
					{key: "time", label: "Logged At", sortable: true, visible: true, "type":"TEXT" },
					{key: "message", label: "Activity", sortable: false, visible: true, "type":"TEXT" },
					{key: "details", label: "Details", sortable: false, visible: true, "type":"TEXT", formatter: "logDetails" }
				],
				sortBy: "time",
				sortDesc: "desc",
				dateFrom: "",
                dateTo: "",
				waitingIndicator: null,
				waitingText: "Loading..."
			}
		}
	}
</script>
