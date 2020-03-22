export default {
	methods: {
		/**
		 *
		 * @param {Number} milliToSleep Number of milliseconds to sleep on this thread
		 * @returns {Promise<any>} An empty promise representing the async thread sleep
		 */
		sleep(milliToSleep) {
			return new Promise(resolve => setTimeout(resolve, milliToSleep));
		}
	}
}