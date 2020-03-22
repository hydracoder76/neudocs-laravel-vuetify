export default {
	methods: {
		projectIDSelected(projectObj) {
			if (this.showTable !== undefined) {
				this.showTable = true;
			}
			if (projectObj !== undefined) {
				this.projectSelected = projectObj;
				this.updatePageChange(1);
			}
			else {
				return false;
			}
		}
	}
}