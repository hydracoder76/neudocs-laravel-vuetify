<template>
	<div class="neu-btn-wrapper" dusk="neu-btn-comp" v-if="isSelected">
		<button type="button" @click.self="btnAction($event)" :class="btnClasses" :dusk="btnDusk">
			<font-awesome-icon :class="iconColorClasses" v-if="faIcon !== ''" :icon="getFaIconFromString(faIcon)"></font-awesome-icon> {{ btnText }}
		</button>
		<slot></slot>
	</div>
</template>

<style scoped>
.neu-btn-wrapper {
	display: inline-block;
}
</style>

<script>
	import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
	import { faPrint, faUpload, faPlus, faArrowLeft, faFile} from "@fortawesome/free-solid-svg-icons";

	export default {
		data() {
			return {
				isSelected : true //TODO: determine if this is still needed
			};
		},
		props: {
			btnText: {
				type: String,
				required: false,
				default: "Button"
			},
			btnType: {
				type: String,
				required: false,
				default: "action"
			},
			btnSize: {
				type: String,
				required: false,
				default: "md"
			},
			btnHref: {
				type: String,
				required: false,
				default: ""
			},
			defaultShow: {
				type: Boolean,
				required: false,
				default: true
			},
			btnDusk: {
				type: String,
				required: false,
				default: "neu-btn"
			},
			faIcon: {
				type: String,
				required: false,
				default: ""
			}
		},
		computed: {
			btnClasses() {
				return {
					"neu-link-button": this.btnType === "action",
					"neu-cancel-button": this.btnType === "cancel",
					"neu-confirm-button": this.btnType === "confirm",
					"neu-btn-md": this.btnSize === "md",
					"neu-btn-sm": this.btnSize === "sm",
					"neu-btn-lg": this.btnSize === "lg"
				}
			},

			iconColorClasses(){
				return {
					"neu-link-button": this.faIcon === "plus",
				}
			}
		},
		methods: {
			btnAction(target) {
				this.$emit('neu-btn-click', target);
				if (this.btnHref !== "") {
					location.href = this.btnHref;
				}
			},
			getFaIconFromString(iconName) {
				switch (iconName) {
					case "print":
						return faPrint;
					case "upload":
						return faUpload;
					case "plus":
						return faPlus;
					case "back":
						return faArrowLeft;
					case "file":
						return faFile;
					default:
						return ""
				}
			},
		},
		mounted(){
			this.isSelected = this.defaultShow;
			this.$root.$on("neu-item-selected", value => {
				this.isSelected = true
			})
		},
		components :{FontAwesomeIcon}
	}
</script>