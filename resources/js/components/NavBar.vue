<template>
	<sidebar-menu :menu="menu" isOpen></sidebar-menu>
</template>
<style>
	#requestsDiv {
		min-height: 700px;
	}

	#sidebar-menu {
		position: relative !important;
		min-height: 700px !important;
	}
</style>
<script>
	import {SidebarMenu} from 'vue-sidebar-menu';
	import { NeuMenuBuilder } from "../classes/neu-menu-builder";
	import IndexBuilder from "./admin/IndexBuilder";



	export default {
		data() {
			return {
				projectList: [],
			}
		},
		computed: {
			menu() {
				const menuBuilder = new NeuMenuBuilder(this.options.roots, this.options.sub);
				return menuBuilder.build();
			},
		},
		mounted() {

			// TODO: there's no need to check here because the projects prop is a required string, please verify
			if (this.projects != null && this.projects != '')
				this.projectList = JSON.parse(this.projects);
			else
				this.projectList = [];
		},
		methods: {},
		props: {
			projects: {
				type: String,
				required: false,
				default: ""
			},
			it: {
				type: Boolean,
				required: false
			},
			childOptions: {
				type: [Array, Object],
				required: false,
				default: null
			},
			options: {
				type: Object,
				required: false, //TODO: for backwards compat while we clean up any rogue dependencies
				default: () => { return {}; }
			}
		},
		components: {
			SidebarMenu, IndexBuilder
		}
	}
</script>