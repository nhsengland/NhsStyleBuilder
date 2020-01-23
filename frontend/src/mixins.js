var _mixins = {
  builder: {
    methods: {
      onClick (event) {
        /**
         * Cancel all selection when click on empty space.
         * Element's onGlobalClick stops propagation.
         */
        this.closeAllElementOptionsPanel(null);
      }
    }
  },
  block: {
    /**
     * Block can contain elements
     */
    methods: {
      removeChild(index) {
        this.Obj.children.splice(index, 1);
        this.$set(this.Obj, 'refresh1', 1);
        this.$delete(this.Obj, 'refresh1');
      },
    }
  },
  fieldCollection: {
    /**
     * fieldCollection extends element
     * It doesn't have optionPanel. It is supposed to display at optionPanel
     */
    data() {
      return {
        // Draggable
        editable: true,
        isDragging: false,
      };
    },
    computed: {
      dragOptions () {
        return  {
          animation: 150,
          group: 'field-collection-' + this.Obj.id,
          disabled: !this.editable,
          ghostClass: 'ghost',
          dragClass: "sortable-drag",
          handle: '.dragme'
        };
      }
    },
    methods: {
      getElementTpl(elementName) {
        if(!this.$options.tpl[elementName]) {
          return null;
        }

        let D = new Date();
        let element = JSON.parse(JSON.stringify(this.$options.tpl[elementName]));
        element.id = Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime();
        return element;
      }
    },
    tpl: {
      TextFieldCollection: {
        component: 'TextFieldCollection',
        contents: {
          Text: '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>'
        },
      },
      ImageFieldCollection: {
        component: 'ImageFieldCollection',
        contents: {
          ImageSrc: 'https://source.unsplash.com/800x600/?nhs+doctor+nurse/' + Math.floor((Math.random() * 100000) + 1),
          ImageUrl: null,
          ImageTitle: null,
          ImageAlt: null
        }
      }
    }
  },
  element: {
    props: ['elements', 'elementsIndex', 'buildingScriptsInfo'],
    data() {
      return {
        // Ref to parent, so 2 way data binding.
        Obj: this.elements[this.elementsIndex],
        elementOnHover: false,
      };
    },
    created() {
      this.$this = this;
      if(!this.Obj.contents) {
        this.Obj.contents = {};
      }
      this.$emit('force-render');
    },
    methods: {
      onGlobalClick(event) {
        event.stopPropagation();
      },
      updateObj(index, src, value) {
        this.Obj[src][index] = value;
        // Manually refresh because options is not reactive due to it's not defined in data{}.
        // options cannot be defined in data{} is because of this.Obj is passed from parent which doesn't have options.
        // options are dynamically added at created().
        this.$set(this.Obj, 'refresh1', 1);
        this.$delete(this.Obj, 'refresh1');

        // This is for block who contains elements, so block manually updates too.
        this.$emit('force-render');
      },
      removeThis() {
        this.$emit('remove', this.elementsIndex);
      },
      __convertVselectObjToArray(obj) {
        let arr = [];
        for(let i in obj) {
          arr.push(obj[i]);
        }
        return arr;
      },
    }
  },
  elementOptionsPanel: {
    data() {
      return {
        showConfigBody: true,
        showContentBody: true,
        showCkeditor: true
      };
    },
    computed: {
      optionClasses() {
        const options = this.Obj.options;
        let optionClasses = [];
        for (let key in options) {
          optionClasses.push(options[key]);
        }
        return optionClasses;
      },
      elementsClassObject() {
        const optionsActive = this.Obj.elementOptionsPanel;
        return {
          'state--mode-configuring': optionsActive.active
        };
      },
    },
    created() {
      // Initialize options.
      if(!this.Obj.options) {
        this.Obj.options = {};
      }

      // Emit to parent to force updating the view and buildingScripts.
      this.$emit('force-render');
    },
    methods: {
      toggleOptionsPanel() {
        this.updateObj('active', 'elementOptionsPanel', !this.Obj.elementOptionsPanel.active);
        if(this.Obj.elementOptionsPanel.active) {
          this.$emit('open-element-options-panel', this.Obj.id);
        }
      },
      closeOptionsPanel() {
        this.updateObj('active', 'elementOptionsPanel', false);
      },
    }
  },
  elementWysiwyg: {
    data() {
      return {
        wysiwygConfig: {
          toolbar: [
            [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'BulletedList']
          ],
          height: 300
        }
      }
    },
  },
  elementOptions: {
    _bgColor: {
      created() {
        this._bgColorLabel = "Background Color";
        this._bgColorAllOptionsObject = {
          'default': {label: 'Default Theme', value: 'bg-default', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color"></div><div class="color-indicator-text">Default Theme</div></div>'},
          'transparent': {label: 'Transparent', value: 'bg-transparent', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-transparent"></div><div class="color-indicator-text">Transparent</div></div>'},
          'shaddy_white': {label: 'Shaddy White', value: 'bg-shaddy_white', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-shaddy_white"></div><div class="color-indicator-text">Shaddy White</div></div>'},
          'light-gray': {label: 'Light Gray', value: 'bg-light-gray', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-light-gray"></div><div class="color-indicator-text">Light Gray</div></div>'},
          'gray': {label: 'Medium Gray', value: 'bg-gray', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-gray"></div><div class="color-indicator-text">Medium Gray</div></div>'},
          'dark-gray': {label: 'Dark Gray', value: 'bg-dark-gray', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-dark-gray"></div><div class="color-indicator-text">Dark Gray</div></div>'},
          'dark-blue': {label: 'Dark Blue', value: 'bg-dark-blue', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-dark-blue"></div><div class="color-indicator-text">Dark Blue</div></div>'},
          'blue': {label: 'Medium Blue', value: 'bg-blue', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-blue"></div><div class="color-indicator-text">Medium Blue</div></div>'},
          'light-blue': {label: 'Light Blue', value: 'bg-light-blue', class: '',
            optionHtml: '<div class="color-indicator"><div class="color-indicator-color bg-light-blue"></div><div class="color-indicator-text">Light Blue</div></div>'},
        };
        this._bgColorAllOptions = this.__convertVselectObjToArray(this._bgColorAllOptionsObject);
      },
    },
    btn_txtPosition: {
      created() {
        this.btn_txtPositionLabel = "Text Alignment";
        this.btn_txtPositionAllOptionsObject = {
          'left': {label: 'Left', value: 'kd_button--txt-align-left', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_left</div><div class="txtAlign-indicator-text">Left</div></div>'},
          'center': {label: 'Center', value: 'kd_button--txt-align-center', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_center</div><div class="txtAlign-indicator-text">Center</div></div>'},
          'right': {label: 'Right', value: 'kd_button--txt-align-right', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_right</div><div class="txtAlign-indicator-text">Right</div></div>'},
        };
        this.btn_txtPositionAllOptions = this.__convertVselectObjToArray(this.btn_txtPositionAllOptionsObject);
      }
    },
    textblock_txtPosition: {
      created() {
        this.textblock_txtPositionLabel = "Text Position";
        this.textblock_txtPositionAllOptionsObject = {
          'left': {label: 'Text at left', value: 'text-left', class: ''},
          'right': {label: 'Text at right', value: 'text-right', class: ''},
        };
        this.textblock_txtPositionAllOptions = this.__convertVselectObjToArray(this.textblock_txtPositionAllOptionsObject);
      }
    },
    iconblock_txtPosition: {
      created() {
        this.iconblock_txtPositionLabel = "Text Alignment";
        this.iconblock_txtPositionAllOptionsObject = {
          'left': {label: 'Left', value: 'icon_block--txt-align-left', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_left</div><div class="txtAlign-indicator-text">Left</div></div>'},
          'center': {label: 'Center', value: 'icon_block--txt-align-center', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_center</div><div class="txtAlign-indicator-text">Center</div></div>'},
          'right': {label: 'Right', value: 'icon_block--txt-align-right', class: '',
            optionHtml: '<div class="txtAlign-indicator"><div class="txtAlign-indicator-icon icon icon_only material-icons">format_align_right</div><div class="txtAlign-indicator-text">Right</div></div>'},
        };
        this.iconblock_txtPositionAllOptions = this.__convertVselectObjToArray(this.iconblock_txtPositionAllOptionsObject);
      }
    },
    _icon: {
      created() {
        this._iconLabel = "Icon";
        let icons = {
          "account": "_icon-account",
          "address": "_icon-address",
          "arrow-down": "_icon-arrow-down",
          "arrow-left": "_icon-arrow-left",
          "arrow-right": "_icon-arrow-right",
          "arrow-top": "_icon-arrow-top",
          "arrow-up": "_icon-arrow-up",
          "call": "_icon-call",
          "chat": "_icon-chat",
          "check": "_icon-check",
          "close": "_icon-close",
          "delta-right": "_icon-delta-right",
          "dots": "_icon-dots",
          "email": "_icon-email",
          "envelop": "_icon-envelop",
          "experience": "_icon-experience",
          "facebook": "_icon-facebook",
          "flag": "_icon-flag",
          "grid": "_icon-grid",
          "home": "_icon-home",
          "linkedin": "_icon-linkedin",
          "list": "_icon-list",
          "lock": "_icon-lock",
          "network": "_icon-network",
          "pin": "_icon-pin",
          "play": "_icon-play",
          "profile": "_icon-profile",
          "remove": "_icon-remove",
          "reset": "_icon-reset",
          "search": "_icon-search",
          "star": "_icon-star",
          "twitter": "_icon-twitter",
          "zoom-in": "_icon-zoom-in",
          "zoom-out": "_icon-zoom-out",
          "collaborate": "_icon-collaborate",
          "guide": "_icon-guide",
          "learning": "_icon-learning",
          "project": "_icon-project",
          "wiki": "_icon-wiki",
          "right-arrow-on-circle": "icon-right-arrow-on-circle",
          "wiki": "_icon-wiki",
          "book": "_icon-book",
          "cog": "_icon-cog",
          "document-edit": "_icon-document-edit",
          "globe-pin": "_icon-globe-pin",
          "megafon": "_icon-megafon",
          "settings": "_icon-settings",
          "tree": "_icon-tree"
        };
        icons['right-arrow-on-circle'] = 'icon-right-arrow-on-circle';
        this._iconAllOptionsObject = {};
        for(let key in icons) {
          this._iconAllOptionsObject[key] = {
            label: key,
            value: 'icon ' + icons[key],
            class: '',
            optionHtml: `<div class="icon-indicator"><div class="icon-indicator-icon icon icon_only ${icons[key]}"></div><div class="icon-indicator-text">${key}</div></div>`
          };
        }
        this._iconAllOptions = this.__convertVselectObjToArray(this._iconAllOptionsObject);
      }
    },
    _iconButtonSize: {
      created() {
        this._iconButtonSizeLabel = "Gap between Icon and Text";
        this._iconButtonSizeAllOptionsObject = {
          'normal': {label: 'Normal', value: 'icon_button_size-normal'},
          'short': {label: 'Short', value: 'icon_button_size-short'}
        };
        this._iconButtonSizeAllOptions = this.__convertVselectObjToArray(this._iconButtonSizeAllOptionsObject);
      }
    }
  },
  dragFix: {
    methods: {
      removeDragFix(event) {
        let dragImageFakeElement = document.querySelectorAll(".dragImageFakeElement");
        for (let element of dragImageFakeElement) {
          element.remove();
        }
        /**
         * Fix ckeditor issue after drop.
         * draggable makes a clone of the component which would lose ckeditor's data.
         * The best practice is to manually make the ckeditor instance reload.
         */
        if(event.item.__vue__
            && event.item.__vue__.Obj
            && event.item.__vue__.Obj.elementOptionsPanel
            && event.item.__vue__.Obj.elementOptionsPanel.active) {
          event.item.__vue__.showCkeditor = false;
          setTimeout(() => {
            event.item.__vue__.showCkeditor = true;
          }, 100);
        }
      },
      applyDragFix(event) {
        this.showCkeditor = false;
        event.item.addEventListener("dragstart", this.makeCloneDragElement, {capture: false, once: true});
      },
      makeCloneDragElement(e) {
        if(e.currentTarget.className === e.srcElement.className) {
          // prevent nested draggable conflicts.
          // child drag also triggers parent's dragstart.
          let crt = document.createElement('div');
          let crtClone = e.currentTarget.cloneNode(true);
          for(let className of crtClone.className.split(' ')) {
            crt.classList.add(className);
          }
          crt.classList.add('dragImageFakeElement');
          crt.appendChild(crtClone.childNodes[0]);
          crtClone.remove();
          e.currentTarget.parentNode.appendChild(crt);
          e.dataTransfer.setDragImage(crt, crt.offsetWidth / 2, crt.offsetHeight / 2);
        }
      }
    }
  }
};
export default _mixins;