export default {
	methods: {
		toSnakeCase(value) {
			return value.toLowerCase().replace(" ", "_");
		},
		queryUrlBuilder(url, values) {
			let returnUrl = url, first = true;
			for (let value in values){
				let token = '&';
				if (first) {
					first = false;
					token = '?';
				}
				returnUrl += token + value + '=' + values[value];
			}
			return returnUrl;
		}
	}
}