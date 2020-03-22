<template>
	<div>

	</div>
</template>

<script>
	export default {
		data() {

		},
		props: {
			fileObj: {
				type: Object,
				required: true
			},
			fileName: {
				type: String,
				required: true
			},
			// TODO: file upload field will be guarded against types, maybe we should do the same here? doubtful
			fileExt: {
				type: String,
				required: true
			}
		}
	}
</script>