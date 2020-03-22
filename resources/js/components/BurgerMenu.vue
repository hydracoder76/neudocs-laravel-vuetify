<template>
    <div>
        <Menu :menu="menu" v-bind="this.$attrs" @openMenu="push" @closeMenu="pull"></Menu>
    </div>
</template>

<script>
    import Menu from './Menu';
	import { NeuMenuBuilder } from "../classes/neu-menu-builder";
	import IndexBuilder from "./admin/IndexBuilder";
    export default {
      name: 'push',
      components: {
        Menu: Menu, IndexBuilder
      },
		data() {
			return {
				projectList: [],
			}
		},
		computed: {
			menu() {
				const menuBuilder = new NeuMenuBuilder(this.options.roots, this.options.sub);
				return menuBuilder.build();
			},
		},
		mounted() {

			// TODO: there's no need to check here because the projects prop is a required string, please verify
			if (this.projects != null && this.projects != '')
				this.projectList = JSON.parse(this.projects);
			else
				this.projectList = [];
		},
      methods: {
        openMenu () {
            this.$emit("openMenu")
        },
        closeMenu () {
            this.$emit("closeMenu")
        },
        push() {
          this.openMenu()
          let width = this.$attrs.width ? this.$attrs.width + 'px' : '300px';

          document.body.style.overflowX = 'hidden';

          if (this.$attrs.right) {
            document.querySelector(
              '#page-wrap'
            ).style.transform = `translate3d(-${width}, 0px, 0px )`;
          } else {
            document.querySelector(
              '#page-wrap'
            ).style.transform = `translate3d(${width}, 0px, 0px )`;
          }

          document.querySelector('#page-wrap').style.transition =
            'all 0.5s ease 0s';
        },
        pull() {
          this.closeMenu()
          document.querySelector('#page-wrap').style.transition =
            'all 0.5s ease 0s';
          document.querySelector('#page-wrap').style.transform = '';
          document.body.removeAttribute('style');
        }
      },
		props: {
			projects: {
				type: String,
				required: false,
				default: ""
			},
			it: {
				type: Boolean,
				required: false
			},
			childOptions: {
				type: [Array, Object],
				required: false,
				default: null
			},
			options: {
				type: Object,
				required: false, //TODO: for backwards compat while we clean up any rogue dependencies
				default: () => { return {}; }
			}
		}
    };
</script>


