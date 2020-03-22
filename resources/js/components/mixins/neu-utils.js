export default {
	methods: {
		isIE(){
			let ua = window.navigator.userAgent;
			const msie = ua.indexOf("MSIE "), trident = ua.indexOf("Trident/");
			return (msie > 0 || trident > 0);
		}
	},
	data() {
		return {
			userRoles: {it: "it", neubus: "neubus", admin: "admin", client: "client"}
		}
	}
}