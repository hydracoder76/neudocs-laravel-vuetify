export default {
	methods: {
		/**
		 * Returns a properly normalized object for event purposes, that way anyone
		 * listening for that event knows what's up
		 *
		 * @param {String} inputType the type of input like text, date, etc
		 * @param {String} value the value of hte input
		 * @returns {{inputType: *, value: *}} the normalized object
		 */
		getEventObj(inputType, value) {
			return {
				inputType: inputType,
				value: value
			};
		}
	}
}