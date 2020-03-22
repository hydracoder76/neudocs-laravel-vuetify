<template>
    <div>
        <div dusk="neu-menu" id="sideNav" class="bm-menu">
            <nav class="bm-item-list">
                <sidebar-menu :menu="menu"></sidebar-menu>
            </nav>
        </div>

        <div class="bm-burger-button" @click="toggleMenu" dusk="neu-burger-btn-open" :class="{ hidden: !burgerIcon }">
            <span class="bm-burger-bars line-style" :style="{top:20 * (index * 2) + '%'}" v-for="(x, index) in 3" :key="index"></span>
        </div>

    </div>
</template>

<script>
	import {SidebarMenu} from 'vue-sidebar-menu';
    export default {
      name: 'menubar',
      data() {
        return {
          isSideBarOpen: false
        };
      },
      props: {
		  menu: {
			  type: Array,
			  required: true
		  },
        isOpen: {
          type: Boolean,
          required: false
        },
        right: {
          type: Boolean,
          required: false
        },
        width: {
          type: [String],
          required: false,
          default: '300'
        },
        disableEsc: {
          type: Boolean,
          required: false
        },
        noOverlay: {
          type: Boolean,
          required: false
        },
        onStateChange: {
          type: Function,
          required: false
        },
        burgerIcon: {
          type: Boolean,
          required: false,
          default: true
        },
        crossIcon: {
          type: Boolean,
          required: false,
          default: true
        },
      },
      methods: {
        toggleMenu(){
          if(!this.isSideBarOpen){
             this.openMenu();
          } else {
             this.closeMenu();
          }
        },
        openMenu() {
          this.$emit('openMenu');
          this.isSideBarOpen = true;

          if (!this.noOverlay) {
            document.body.className += 'bm-overlay';
          }
          if (this.right) {
            document.querySelector('.bm-menu').style.left = 'auto';
            document.querySelector('.bm-menu').style.right = '0px';
          }
          this.$nextTick(function() {
            document.getElementById('sideNav').style.width = this.width
              ? this.width + 'px'
              : '300px';
              document.getElementById('page-wrap').style.marginLeft = this.width
              ? this.width + 'px'
              : '300px';
              document.getElementById('page-wrap').style.transform = 'none';
          });
        },

        closeMenu() {
          this.$emit('closeMenu');
          this.isSideBarOpen = false;
          document.body.className = document.body.className.replace(
            'bm-overlay',
            ''
          );
          document.getElementById('sideNav').style.width = '0px';
          document.getElementById('page-wrap').style.marginLeft = '0px';
          document.getElementById('page-wrap').style.transform = 'none';
        },

        closeMenuOnEsc(e) {
          e = e || window.event;
          if (e.key === 'Escape' || e.keyCode === 27) {
            document.getElementById('sideNav').style.width = '0px';
            document.getElementById('page-wrap').style.marginLeft = '0px';
            document.getElementById('page-wrap').style.transform = 'none';
            document.body.style.backgroundColor = 'inherit';
            this.isSideBarOpen = false;
          }
        },
      
      },

      mounted() {
        if (!this.disableEsc) {
          document.addEventListener('keyup', this.closeMenuOnEsc);
        }
      },
      created: function() {
        document.addEventListener('click', this.documentClick);
      },
      destroyed: function() {
        document.removeEventListener('keyup', this.closeMenuOnEsc);
        document.removeEventListener('click', this.documentClick);
      },
      watch: {
        isOpen: {
          deep: true,
          immediate: true,
          handler(newValue, oldValue) {
            if (!oldValue && newValue) {
              this.openMenu()
            }
            if (oldValue && !newValue) {
              this.closeMenu()
            }
          }
        },
        right: {
          deep: true,
          immediate: true,
          handler(oldValue, newValue) {
            if (oldValue) {
              this.$nextTick(() => {
                document.querySelector('.bm-burger-button').style.left = 'auto';
                document.querySelector('.bm-burger-button').style.right = '36px';
                document.querySelector('.bm-menu').style.left = 'auto';
                document.querySelector('.bm-menu').style.right = '0px';
              });
            }
            if (newValue) {
              if (
                document.querySelector('.bm-burger-button').hasAttribute('style')
              ) {
                document
                  .querySelector('.bm-burger-button')
                  .removeAttribute('style');
                document.getElementById('sideNav').style.right = 'auto';
              }
            }
          }
        }
      },
      components: {
			SidebarMenu
      }
    };
</script>

<style>
    html {
      height: 100%;
    }
    .bm-burger-button {
      position: fixed;
      width: 30px;
      height: 24px;
      left: 36px;
      z-index: 2;
      top: 20px;
      cursor: pointer;
    }
    .bm-burger-button.hidden {
      display: none;
    }
    .bm-burger-bars {
      background-color: #fff;
    }
    .line-style {
      position: absolute;
      height: 20%;
      left: 0;
      right: 0;
    }
    .cross-style {
      position: absolute;
      top: 12px;
      right: 2px;
      cursor: pointer;
    }
    .bm-cross {
      background: #bdc3c7;
    }
    .bm-cross-button {
      height: 24px;
      width: 24px;
    }
    .bm-cross-button.hidden {
      display: none;
    }
    .bm-menu {
      height: 100%; /* 100% Full-height */
      width: 0; /* 0 width - change this with JavaScript */
      position: fixed; /* Stay in place */
      z-index: 0;
      top: 70px;     /* position for top with respect to header height*/
      left: 0;
      border-right: 1px solid #ccc;
      overflow-x: hidden; /* Disable horizontal scroll */
      transition: 0.5s; /*0.5 second transition effect to slide in the sidenav*/
    }

    .bm-overlay {
      /* background: rgba(0, 0, 0, 0.3); */
    }
    .bm-item-list {
      color: #b8b7ad;
      margin-left: 1%;
      font-size: 20px;
    }
    .bm-item-list > * {
      display: flex;
      text-decoration: none;
      padding: 0;
    }
    .bm-item-list > * > span {
      margin-left: 10px;
      font-weight: 700;
      color: white;
    }
</style>

