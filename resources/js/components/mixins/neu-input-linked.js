/* eslint no-undef:off,no-invalid-this:off */
// vue mixin, don't need some of these things
/**
 * This mixin allows input fields to be split in to multiple pieces and allows a cursor
 * to automatically jump between them
 * TODO: link with MfaForm.vue during the "mfa form issues" story
 */


export default {

	data() {
		return {
			runningTokenStringSize: 0,
			numLinkedFields: 6,
			tokenPieces: []
		}
	},
	methods: {
		tokenPieceEntered(index, $event) {

			if ($event.keyCode === 9 || $event.keyCode === 16){
				return false;
			}
			if ($event.keyCode === 8){


				if (this.runningTokenStringSize > 0) {
					Vue.set(this.tokenPieces, index, "");
					this.runningTokenStringSize -= 1;
					this.$emit("neu-linked-input");
					this.moveToPrevRelativeTo(index);
				}
				else {
					this.$emit("neu-linked-input");
					this.resetLinkedFields();
				}
				return false;
			}

			else if ((($event.keyCode <= 47 || $event.keyCode >= 58) && ($event.keyCode <= 95 || $event.keyCode >= 106))){
				if (this.runningTokenStringSize > 0) {
					this.runningTokenStringSize -= 1;
					this.$emit("neu-linked-input");
				}
				else {
					this.resetLinkedFields();
				}

				return false;
			}
			else {

			}
			let keyCode = $event.keyCode;
			if (keyCode >= 96 && keyCode <= 105){
				keyCode -= 48;
			}

			if (this.runningTokenStringSize < this.numLinkedFields) {
				this.runningTokenStringSize += 1;
				if (this.tokenPieces.length > 0 && this.tokenPieces[index] !== "undefined" && this.tokenPieces[index] !== "") {
					this.moveToNextRelativeTo(index);
				}
				Vue.set(this.tokenPieces, index, String.fromCharCode(keyCode));
			}

			this.$emit("neu-linked-input");
			return false;

		},
		moveToNextRelativeTo(position) {
			const element = document.querySelector("#" + this.fieldIdPrefix + (position + 1));
			if (element !== null) {
				element.focus();
			}
		},
		moveToPrevRelativeTo(position) {
			const element = document.querySelector("#" + this.fieldIdPrefix + (position - 1));
			if (element !== null) {
				element.focus();
			}
		},
		resetLinkedFields() {
			this.runningTokenStringSize = 0;
			this.tokenPieces = [];
		},
        resetCursorPosition() {
            const element = document.querySelector("#" + this.fieldIdPrefix + "0");
            if (element !== null) {
                element.focus();
            }
        }
	}
}
