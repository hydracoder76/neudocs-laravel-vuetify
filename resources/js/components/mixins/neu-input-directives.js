/* eslint no-param-reassign: off*/
export default {
	directives: {
		numeric: {
			bind(el) {

				el.type = "number";
				el.addEventListener("input", () => {
					return el.validity.valid || (el.value = "");
				});

				el.addEventListener("keypress", (event) => {
					const charCode = event.which || event.keyCode;
					if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
						event.preventDefault();
						return false;
					}

					return true;

				});
			}
		}
	}
}