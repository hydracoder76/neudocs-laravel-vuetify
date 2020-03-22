<template>
	<neu-table title="Manage Project"
			  tabname="Project"
			  type="edit"
			  :data="dataTable"
			  :columns="dataColumns"
			  @add-ok="add"
			  @edit-ok="edit"
			  @delete-ok="deleteFn"
			  class="vm-margin">
	</neu-table>
</template>

<script>
	import NeuTable from "./util/NeuTable"
	import NavBar from "./NavBar"

	export default {
		name: 'EditableProjectTable',
		components: {
			NeuTable, NavBar
		},
		methods: {
			add (data) {
				this.dataTable.unshift(data)
			},
			edit(data) {
				this.dataTable.forEach(function (elem) {
					if (elem.id === data.id) {
						for (let i in data) {
							elem[i] = data[i]
						}
					}
				})
			},
			deleteFn (data) {
				let tmpDataTable = this.dataTable
			    let i=0
                this.dataTable.forEach(function (elem) {
                    data.forEach(function (elem2) {
                        if (elem.id === elem2.id) {
							tmpDataTable.splice(i,1)
                        }
                    })
                    i++
                })
				this.dataTable=tmpDataTable
			}
		},
		data() {
			return {
				dataTable: [
					{
						id: '65416s843154',
                        projectname: 'Anthony',
						abbreviation: 'AN',
						city: 'Round Rock',
						state: 'Texas',
						zip: '78664'
					}
				],
				dataColumns: [
					{key: 'projectname', label: 'Project Name', sortable: true, sortDirection: 'desc'},
					{key: 'abbreviation', label: 'Abbreviation', sortable: true,},
					{key: 'city', label: 'City'},
					{key: 'state', label: 'State'},
                    {key: 'zip', label: 'Zip'},
                    {key: 'actions', label: 'Actions'}
				]
			}
		}
	}
</script>
