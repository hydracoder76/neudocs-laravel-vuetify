/**
 * This builder is intended to construct trees of options for nav bars
 * for the time being it uses the vue navbar we're already using as a basis
 */
const menu = Symbol("menu"),
	transformedMenu = Symbol("transformedMenu"),
	roots = Symbol("urlRoots");

export class NeuMenuBuilder {

	/**
	 * @param {String} urlRoots the root portion of the url
	 * @param {Object} menuJson The raw data to build the menu out of
	 */
	constructor(urlRoots, menuJson) {
		this[menu] = {};
		if (typeof menuJson !== "object") {
			throw new Error("Invalid type, param must be an object")
		}
		this[menu] = menuJson;
		this[transformedMenu] = [];
		this[roots] = urlRoots;
	}

	/**
	 *
	 * @param {Object} schemaJsonDef json.org schema definition of what a menu should look like
	 * @returns {void}
	 */
	setSchema(schemaJsonDef) {
		throw new Error("Not yet implemented");
	}

	/**
	 *
	 * @param {Object} menuJson The raw data to build the menu out of
	 * @returns {void}
	 */
	setMenuJson(menuJson) {
		if (typeof menuJson !== "object") {
			throw new Error("Invalid type, param must be an object")
		}
		this[menu] = menuJson;
	}

	/**
	 *
	 * @returns {object} The generated menu
	 */
	build() {
		this.resetMenu();
		let allIndex = 0;
		Object.keys(this[menu]).forEach((menuKey, menuIndex) => {
			Object.keys(this[menu][menuKey]).forEach((key, index) => {
				let parentHref = (this[menu][menuKey][key].href === '') ? '' : '/';
				this[transformedMenu].push({
					href: "/" + this[roots][menuKey] + parentHref + this[menu][menuKey][key].href,
					title: this[menu][menuKey][key].title,
					icon: this[menu][menuKey][key].icon
				});

				if (this[menu][menuKey][key].hasOwnProperty("child")) {
					this[transformedMenu][allIndex].child = this[menu][menuKey][key].child;

					this[transformedMenu][allIndex].child.forEach((item, ind) => {
						let childHref = (this[transformedMenu][allIndex].href === '') ? '' : '/';
						this[transformedMenu][allIndex].child[ind].href =
							this[transformedMenu][allIndex].href + childHref + this[transformedMenu][allIndex].child[ind].href;
					});
				}
				allIndex += 1;
			});
		});

		return this[transformedMenu];
	}

	/**
	 *
	 * @returns {object} The currently generated menu
	 */
	getCurrentMenu() {
		return this[transformedMenu];
	}

	/**
	 * @return {void}
	 */
	resetMenu() {
		this[transformedMenu] = [];
	}


}