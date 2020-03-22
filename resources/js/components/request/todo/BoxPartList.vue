<template>
    <div>
        Box Number: {{ boxNumber }}<br>
        Requests: {{requests}}
        <slot name="part-file-list"></slot>

    </div>
</template>

<script>
    import PartFileList from "./PartFileList";
	export default {
		data() {
			return {

            };
        },
        components: {
			PartFileList
        },
        props: {
			partList: {
				type: Object,
                required: true,
                required: false
            },
            boxNumber: {
				type: String,
                required: true
            },
            requests:{
				type:String,
                required:true,
            }
        }
    }
</script>