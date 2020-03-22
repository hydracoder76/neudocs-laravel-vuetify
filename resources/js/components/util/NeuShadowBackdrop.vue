
<template>
	<div v-if="show" class="neu-shadow-cont">
		<div :class="shadowType">
		</div>
		<div class="neu-shadow-cont-label">
			<font-awesome-icon :icon="iconToUse" size="2x" spin></font-awesome-icon>
			<p :style="{'color':shadowTextColor, 'z-index':'2', 'opacity':'1'}">{{ shadowText }}</p>
		</div>
	</div>
</template>

<style scoped>
	.neu-shadow-cont {
		position: relative;
	}
	.neu-shadow-cont-label {
		position: absolute;
		top: 0;
		left: 50%;
		z-index: 1050;
	}
</style>
<script>
	/* eslint vue/script-indent: "off", complexity: "off" */

	import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
	import { faSpinner, faAsterisk, faCertificate, faCircleNotch, faCog,
		faCompass, faCrosshairs, faLifeRing, faSnowflake, faSun, faSync} from "@fortawesome/free-solid-svg-icons";
	export default {
		data() {
			return {

			};
		},
		computed: {
			shadowType() {
				return "neu-" + this.color + "-shadow";
			},
			iconToUse() {
				// it's just a switch statement to figure out what icon to import
				switch (this.shadowIcon) {
					case "spinner":
						return faSpinner;
					case "asterisk":
						return faAsterisk;
					case "certificate":
						return faCertificate;
					case "circle-notch":
						return faCircleNotch;
					case "cog":
						return faCog;
					case "compass":
						return faCompass;
					case "crosshairs":
						return faCrosshairs;
					case "life-ling":
						return faLifeRing;
					case "snowflake":
						return faSnowflake;
					case "sun":
						return faSun;
					case "sync":
						return faSync;
					default:
						return faSpinner;
				}
			}
		},
		props: {
			show: {
				type: Boolean,
				required: false,
				default: false
			},
			color: {
				type: String,
				required: false,
				default: "black",
				validator: function(value) {
					return ["black"].indexOf(value) !== -1;// just add to this if more base colors are needed
				}
			},
			shadowText: {
				type: String,
				required: false,
				default: "#Loading..."
			},
			shadowTextColor: {
				type: String,
				required: false,
				default: "#ffffff"
			},
			shadowIcon: {
				type: String,
				required: false,
				default: "spinner",
				validator: function(value) {
					return ["spinner", "asterisk", "certificate", "circle-notch", "cog", "compass",
						"crosshairs", "life-ring", "snowflake", "sun", "sync"].indexOf(value) !== -1;
				}
			}
		},
		components: {
			FontAwesomeIcon
		}
	}
</script>