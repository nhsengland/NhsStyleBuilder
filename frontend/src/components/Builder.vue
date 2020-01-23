<template>
  <div class="builderContainer" @click="onClick">
    <span v-html="themeStyle"></span>{{themeStyleWatch}}
    <div class="main-column">
      <div class="maincontent">
        <div class="builder-body container" :class="isDragging ? 'state--row-is-dragging' : ''">
          <draggable
            class="list-group"
            element="div"
            v-model="buildingScripts.children"
            :options="dragOptions"
            :move="onMove"
            @start="isDragging=true"
            @choose="closeAllElementOptionsPanel(null)"
            @end="isDragging=false">
            <template v-for="(child, index) in buildingScripts.children">
              <component
                :is="child.component"
                :key="child.id"
                :building-scripts="buildingScripts.children"
                :building-scripts-index="index"
                :building-scripts-info="buildingScripts.projectInfo"
                @force-render="forceRender"
                @open-element-options-panel="openElementOptionsPanel"
                @close-all-element-options-panel="closeAllElementOptionsPanel(null)"
                @remove="removeRow"
              ></component>
            </template>
          </draggable>
          <a class="builder-add-row builder-helper-container" @click="addRow()">New Row</a>
        </div>
        <div style="clear: both;"></div>
      </div>
    </div>
    <div class="console">
      <div class="console-nav">
        <a v-if="!showExportPanel" class="console-export"
          @click="generateHtml();">
          <span>Export</span>
        </a>
      </div>

      <div v-if="showOptionsPanel">
        <div class="console-container">
          <input type="text" v-model="buildingScripts.projectInfo.title" class="title-input"/>
        </div>

        <div class="console-container">
          <a
            class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
            :class="showProjectConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
            @click = "showProjectConfigBody = !showProjectConfigBody"
            >
            <span>
            Workspace info
            </span>
          </a>
        </div>
        <transition name="slide-fade">
          <div class="console-configbody console-container" v-if="showProjectConfigBody">
            <label>Logo url:</label>
            <br>
            <img width="100%" v-if="buildingScripts.projectInfo.projectLogoUrl" :src="buildingScripts.projectInfo.projectLogoUrl" />
            <br>
            <input
              placeholder="Workspace logo url"
              v-model="buildingScripts.projectInfo.projectLogoUrl"
              class="inline-input" />
            <br />
            <br />
            <label>Visibility statement:</label>
            <br>
            <input
              placeholder="Workspace visibility statement"
              v-model="buildingScripts.projectInfo.projectVisibility"
              class="inline-input" />
            <br />
            <br />
            <label>Members total number:</label>
            <br>
            <input
              placeholder="Workspace members total number"
              v-model="buildingScripts.projectInfo.projectMemberCount"
              class="inline-input" />
            <br />
            <br />
            <label>Workspace url:</label>
            <br>
            <input
              placeholder="Workspace url"
              v-model="buildingScripts.projectInfo.projectHomeUrl"
              class="inline-input" />
            <br />
            <br />
            <label>Contact email:</label>
            <br>
            <input
              placeholder="Workspace contact email"
              v-model="buildingScripts.projectInfo.projectContactEmail"
              class="inline-input" />
          </div>
        </transition>

        <div class="console-container">
          <a
            class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
            :class="showThemeConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
            @click = "showThemeConfigBody = !showThemeConfigBody"
            >
            <span>
            Theme color
            </span>
          </a>
        </div>
        <transition name="slide-fade">
          <div class="console-configbody" v-if="showThemeConfigBody">
            <div class="kd_columns kd_columns-2">
              <div class="kd_col">
                <p>Primary Color</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.primary_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.primary_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Secondary Color</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.secondary_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.secondary_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Tertiary Color</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.tertiary_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.tertiary_color" class="color-input"/>
                </div>
              </div>
            </div>
          </div>
        </transition>
        <div class="console-container">
          <a
            class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
            :class="showFontConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
            @click = "showFontConfigBody = !showFontConfigBody"
            >
            <span>
            Font
            </span>
          </a>
        </div>
        <transition name="slide-fade">
          <div class="console-configbody console-container" v-if="showFontConfigBody">
            <h1>Heading 1</h1>
            <div class="kd_columns kd_columns-2">
              <div class="kd_col">
                <p>Colour</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.h1_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.h1_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Font-size</p>
                <div class="color-palette-indicator">
                  <select v-model="buildingScripts.projectOptions.h1_font_size" class="color-input">
                    <option value="48px">48</option>
                    <option value="32px">32</option>
                  </select>
                </div>
              </div>
            </div>
            <h2>Heading 2</h2>
            <div class="kd_columns kd_columns-2">
              <div class="kd_col">
                <p>Colour</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.h2_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.h2_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Font-size</p>
                <div class="color-palette-indicator">
                  <select v-model="buildingScripts.projectOptions.h2_font_size" class="color-input">
                    <option value="32px">32</option>
                    <option value="24px">24</option>
                  </select>
                </div>
              </div>
            </div>
            <h3>Heading 3</h3>
            <div class="kd_columns kd_columns-2">
              <div class="kd_col">
                <p>Colour</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.h3_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.h3_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Font-size</p>
                <div class="color-palette-indicator">
                  <select v-model="buildingScripts.projectOptions.h3_font_size" class="color-input">
                    <option value="24px">24</option>
                    <option value="22px">22</option>
                  </select>
                </div>
              </div>
            </div>
            <p>Paragraph</p>
            <div class="kd_columns kd_columns-2">
              <div class="kd_col">
                <p>Colour</p>
                <div class="color-palette-indicator">
                  <div :style="{backgroundColor: buildingScripts.projectOptions.p_color}"></div>
                  <input type="text" v-model="buildingScripts.projectOptions.p_color" class="color-input"/>
                </div>
              </div>
              <div class="kd_col">
                <p>Font-size</p>
                <div class="color-palette-indicator">
                  <select v-model="buildingScripts.projectOptions.p_font_size" class="color-input">
                    <option value="19px">19</option>
                    <option value="15px">15</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>

      <div class="console-container generated-html-container" v-if="showExportPanel">
        <div class="console-control">
          <a v-if="html !== null" class="icon icon_only material-icons float-left"
            @click="html = null; copySucceeded = null;">arrow_back</a>
        </div>
        <textarea class="generated-html-body" v-model="html" @focus="$event.target.select()">
        </textarea>
        <p style="text-align: center;" v-if="copySucceeded === false">Copy error</p>
        <a v-if="html !== null && html != ''" class="console-btn"
          @click="doCopy(html);">
          <span v-if="copySucceeded !== true">Click to copy</span>
          <span v-if="copySucceeded === true">Copied!</span>
        </a>
      </div>

      <div class="console-container" v-if="showImportPanel">
        <a v-if="showImportPanel" class="icon _icon-arrow-left normal icon_only"
          @click="showImportPanel = false;">
        </a>
        <textarea v-model="importedHtml" class="generated-html-body" placeholder="Paste entire html here">
        </textarea>
        <a class="console-btn" @click="importSubmit"><span>Submit</span></a>
      </div>

      <div class="console-container" v-if="!showImportPanel && !showExportPanel">
        <a class="console-btn" @click="openImportPanel">
          <span>Import</span>
        </a>
      </div>

      <a class="tempButton" @click="resetWorkspace">New workspace</a>
    </div>
  </div>
</template>

<script>
import cssColorFunc from 'css-color-function';
import debounce from '../customModules/debounce';
import { default as _mixins } from '../mixins';
export default {
  name: 'builder',
  mixins: [
    _mixins.builder,
  ],
  data () {
    let buildingScripts = require('../assets/sample-workspace.json');
    buildingScripts.environment = window._environment;
    return {
      serviceUrl: window.assetsSrc,

      buildingScripts: buildingScripts,

      html: null,
      copySucceeded: null,
      importedHtml: null,

      showProjectConfigBody: false,
      showThemeConfigBody: false,
      showGrayConfigBody: false,
      showBlueConfigBody: false,
      showFontConfigBody: false,

      // Draggable
      editable:true,
      isDragging: false,

      // Tab states
      showImportPanel: false,

      themeStyle: null,
      debouncerId: null,
    }
  },
  components: {
    'RowBlock': () => import('./RowBlock'),
    'ColBlock': () => import('./ColBlock')
  },
  computed: {
    dragOptions () {
      return  {
        animation: 150,
        group: 'row',
        disabled: !this.editable,
        ghostClass: 'ghost',
        dragClass: "sortable-drag",
        handle: '.row-drag-handle'
      };
    },
    showExportPanel() {
      const html = this.html;
      return html !== null ? true : false;
    },
    showOptionsPanel() {
      const showExportPanel = this.showExportPanel;
      const showImportPanel = this.showImportPanel;
      return !showExportPanel && !showImportPanel;
    },
    themeStyleWatch() {
      var vm = this;
      if(!this.buildingScripts || !this.buildingScripts.projectOptions) {
        return null;
      }
      let data = this.prepareThemeStyleData();
      this.debouncerId = debounce(function() {
        axios.post(vm.serviceUrl + 'api/generate_theme.php', data)
        .then(function (response) {
          vm.themeStyle = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      }, 500)(this.debouncerId);

      return null;
    }
  },
  methods: {
    prepareThemeStyleData() {
      let data = {};
      for(let key in this.buildingScripts.projectOptions) {
        if(key.match(/_color$/i)) {
          data[key + '_hover'] = this.getHoverColor(key);
        }
        data[key] = this.buildingScripts.projectOptions[key];
      }
      return data;
    },
    getHoverColor(colorKey) {
      const color = this.buildingScripts.projectOptions[colorKey];
      let returnColor = null;
      if(color) {
        try{
          returnColor = cssColorFunc.convert(`color(${color} shade(20%))`);
        }
        catch(e) {
          console.log(e);
        }
      }
      return returnColor;
    },
    resetWorkspace() {
      this.buildingScripts = require('../assets/new-workspace.json');
      this.buildingScripts.environment = window._environment;
      this.copySucceeded = null;
      this.html = null;
    },
    openImportPanel() {
      this.closeAllElementOptionsPanel(null);
      this.html = null;
      this.showImportPanel = true;
    },
    addRow() {
      let D = new Date();
      this.buildingScripts.children.push(
        {
          component: 'RowBlock',
          id: Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime(),
          elementAddingModeActive: true
        }
      );
    },
    removeRow(index) {
      this.buildingScripts.children.splice(index, 1);
    },
    generateHtml() {
      this.showImportPanel = false;
      this.closeAllElementOptionsPanel(null);
      // console.log(JSON.stringify(this.buildingScripts));

      var vm = this;
      axios.post(this.serviceUrl + 'api/generate.php', this.buildingScripts)
      .then(function (response) {
        vm.html = (response.data);
      })
      .catch(function (error) {
        vm.html = 'Error! Could not reach the API. ' + error.statusText;
      });
    },
    importSubmit() {
      var vm = this;
      axios.post(this.serviceUrl + 'api/import.php', {importedHtml: this.importedHtml})
      .then(function (response) {
        vm.buildingScripts = response.data;
        if(!vm.buildingScripts.projectOptions) {
          vm.buildingScripts.projectOptions = {};
        }
        vm.forceRender();
        vm.importedHtml = null;
        vm.showImportPanel = false;
      })
      .catch(function (error) {
        vm.importingMsg = 'Error! Could not reach the API. ' + error;
      });
    },
    forceRender() {
      this.buildingScripts.children.push({});
      this.buildingScripts.children.pop();
    },
    openElementOptionsPanel(elementId, src) {
      // Close other elements's option panel
      if(!src) {
        src = this.buildingScripts.children
      }
      for(let index in src) {
        if(src[index].elementOptionsPanel && src[index].elementOptionsPanel.active && src[index].id != elementId) {
          this.closeElementOptionsPanel(index, src);
        }

        if(src[index].children && src[index].children.length >0) {
          this.openElementOptionsPanel(elementId, src[index].children);
        }
      }
    },
    closeElementOptionsPanel(index, src) {
      if(src[index].elementOptionsPanel) {
        src[index].elementOptionsPanel.active = false;
      }
    },
    closeAllElementOptionsPanel(src) {
      if(!src) {
        src = this.buildingScripts.children;
      }
      for(let index in src) {
        if(src[index].children && src[index].children.length >0) {
          this.closeAllElementOptionsPanel(src[index].children);
        }
        this.closeElementOptionsPanel(index, src);
      }
    },
    handleCopyStatus(status) {
      this.copySucceeded = status;
    },
    doCopy(text) {
      let vm = this;
      this.$copyText(text).then(function(e) {
        vm.handleCopyStatus(true);
      }, function(e) {
        vm.handleCopyStatus(false);
      });
    },
    onMove ({relatedContext, draggedContext}, event) {
      const relatedElement = relatedContext.element;
      const draggedElement = draggedContext.element;
      return (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
    }
  }
}
</script>

<style lang="postcss">
@import '../sass/style';
</style>

<style lang="postcss" scoped>
@import '../sass/Builder/scoped';
</style>
