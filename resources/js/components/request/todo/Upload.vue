<template>
	<div class="neu-part-upload">
		<div class="neu-request-files">
			<slot name="list-header"></slot>
            <slot name="box-part-list"></slot>
		</div>
	</div>
</template>

<script>

	import NeuTable from "../../util/NeuTable";
	import NeuButton from "../../util/NeuButton";
	import UploadFileForm from "../../forms/form-components/UploadFileForm";
	import PartFileList from "./PartFileList";

	// TODO: refactor NeuTable so that this is a bit cleaner
	export default {
		data() {
			return {

				currentTableData: [],
				columnData: [
					{ partName: "Box A", indexInfo: "index_1", selectFile: "file_1.pdf"},
					{ boxName: "Box B", indexInfo: "index_2", selectFile: "file_2.pdf"}

				],
				columnNames: [
					{key: "boxName", label: "Box Name", visible: true,},
					{key: "indexInfo", label: "Index Info", visible: true},
					{
						key: "selectFile",
						label: "Select File",
						visible: true,
						useSlot: true,// in order to use the below properties,
						// useSlot must be true, else a normal cell is rendered
						icon: "trash-alt",
						eventName: "neu-delete-file",
						eventPlaceHolderIcon: "cog",
						styles: "neu-delete-icon"
					}
				]
			};
		},
		props: {


		},
		components: {
			NeuTable, UploadFileForm, NeuButton, PartFileList
		}
	}
</script>