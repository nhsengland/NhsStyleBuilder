<template>
  <div class="row-block z-block z-component"
    :class="rowClasses"
    @mouseover="rowIndicatorActive = true"
    @mouseout="rowIndicatorActive = false"
    @click="onGlobalClick"
    >
    <div class="inline-element-control row-drag-handle">
      <div class="control-btn float-left">
        <a class="icon icon_only material-icons"
          @click="toggleElementAddingMode"
        >{{addingButtonVisibility}}</a>
      </div>
      <div class="control-btn float-left" :class="columnLayoutClassObject">
        <a class="icon icon_only material-icons element-config--btn"
          @click="columnLayoutSelectorActive = true"
        >view_column</a>
        <div class="options">
          <v-select
            class="column-layout-selection"
            :options="columnLayoutOptions"
            label="label"
            v-model="Obj.options.columnLayout"
            @input="generateCols"
            >
            <template slot="option" slot-scope="option">
              <span v-html="option.optionHtml"></span>
            </template>
          </v-select>
        </div>
      </div>
      <div class="control-btn float-right">
        <a class="icon icon_only material-icons element-remove--btn"
          @click="removeThis()"
        >delete_outline</a>
      </div>
    </div>
    <div class="builder-body">
      <div class="kd_columns" :class="columnsClasses">
        <template v-for="(child, index) in Obj.children">
          <component
            :is="child.component"
            :key="child.id"
            :columns="Obj.children"
            :columns-index="index"
            :building-scripts-info="buildingScriptsInfo"
            @force-render="forceRender"
            @open-element-options-panel="openElementOptionsPanel"
            @close-all-element-options-panel="closeAllElementOptionsPanel"
            @remove="removeColumn"
          ></component>
        </template>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'row-block',
  props: ['buildingScripts', 'buildingScriptsIndex', 'buildingScriptsInfo'],
  data() {
    let Obj = this.buildingScripts[this.buildingScriptsIndex];
    let elementAddingModeActive = (Obj.elementAddingModeActive) ? true : false;
    Obj.elementAddingModeActive = false;
    return {
      // Ref to parent, so 2 way data binding.
      Obj: Obj,
      columnLayoutSelectorActive: false,
      rowIndicatorActive: false,
      /**
       * elementAddingModeActive is not saved in buildingScript.
       */
      elementAddingModeActive: elementAddingModeActive
    }
  },
  components: {'ColBlock': () => import('./ColBlock')},
  computed: {
    addingButtonVisibility() {
      const elementAddingModeActive = this.elementAddingModeActive;
      let output = elementAddingModeActive ? 'visibility' : 'visibility_off';
      return output;
    },
    columnLayoutClassObject() {
      return {
        'state--expand-to-selection': this.columnLayoutSelectorActive
      }
    },
    columnLayoutOptions() {
      const columns = this.Obj.children;
      let columnsLength = 0;
      if(columns) {
        columnsLength = columns.length;
      }
      let options = [];
      switch(columnsLength) {
        case 0:
        case 1:
          options.push(this.allOptions['1/1']);
        case 2:
          options.push(this.allOptions['1/2']);
          options.push(this.allOptions['1/3+2/3']);
          options.push(this.allOptions['2/3+1/3']);
        case 3:
          options.push(this.allOptions['1/3']);
        case 4:
          options.push(this.allOptions['1/4']);
        case 5:
          options.push(this.allOptions['1/5']);
      }
      return options;
    },
    columnsClasses() {
      const columns = this.Obj.children;
      const columnLayout = this.Obj.options.columnLayout;
      let columnsLength = 0;
      if(columns) {
        columnsLength = columns.length;
      }
      let classes = [];
      classes.push('kd_columns-' + columnsLength);
      if(columnLayout && columnLayout.value) {
        switch(columnLayout.value){
          case '1/3+2/3':
            classes.push('kd_columns_partition-1-2');
          break;
          case '2/3+1/3':
            classes.push('kd_columns_partition-2-1');
          break;
        }
      }
      if(this.rowIndicatorActive) {
        classes.push('builder-helper-container');
        classes.push('builder-helper-container-active');
      }
      else {
        this.columnLayoutSelectorActive = false;
      }
      return classes;
    },
    rowClasses() {
      const elementAddingModeActive = this.elementAddingModeActive;
      let classes = [];
      if(this.rowIndicatorActive) {
        classes.push('state--row-on-hover');
      }
      if(elementAddingModeActive) {
        classes.push('state--row-on-element-adding');
      }
      return classes;
    }
  },
  methods: {
    onGlobalClick(event) {
      event.stopPropagation();
    },
    toggleElementAddingMode(event) {
      event.stopPropagation();
      this.elementAddingModeActive = !this.elementAddingModeActive;
    },
    addCols() {
      let D = new Date();
      this.Obj.children.push({component: 'ColBlock', id: Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime()});
      // Above udpates children but not options. So, refresh harder.
      this.forceRender();
    },
    generateCols() {
      if(this.Obj.options.columnLayout && this.cachedColumnLayout != this.Obj.options.columnLayout.value) {
        this.cachedColumnLayout = this.Obj.options.columnLayout.value;
        this.cachedColumnLayoutObject = this.Obj.options.columnLayout;
        // Generate columns based on options.columnLayout
        let selectedCols = this.allOptions[this.Obj.options.columnLayout.value].cols;
        let cols = this.Obj.children.length;
        if(cols < selectedCols) {
          for(let i = 0; i < (selectedCols - cols); i++) {
            this.addCols();
          }
        }
      }
      else {
        this.Obj.options.columnLayout = this.cachedColumnLayoutObject;
        // Force update columnLayout for v-select's v-model
        this.$set(this.Obj.options.columnLayout, 'refresh1', 1);
        this.$delete(this.Obj.options.columnLayout, 'refresh1');
      }
      this.columnLayoutSelectorActive = false;
    },
    forceRender() {
      this.Obj.children.push({});
      this.Obj.children.pop();
      this.$set(this.Obj, 'refresh1', 1);
      this.$delete(this.Obj, 'refresh1');
      this.$emit('force-render');
    },
    removeThis() {
      this.$emit('remove', this.buildingScriptsIndex);
    },
    removeColumn(index) {
      this.Obj.children.splice(index, 1);
      // update column layout
      let colsTotal = this.Obj.children.length;
      this.Obj.options.columnLayout = this.allOptions['1/' + colsTotal];
      if(colsTotal === 0) {
        this.removeThis();
      }
      this.forceRender();
    },
    openElementOptionsPanel(elementId) {
      this.$emit('open-element-options-panel', elementId);
    },
    closeAllElementOptionsPanel() {
      this.$emit('close-all-element-options-panel');
    }
  },
  created() {
    this.allOptions = {
      '1/1': {label: '1 column', value: '1/1', class: '', cols: 1, optionHtml: null},
      '1/2': {label: '2 columns', value: '1/2', class: 'kd_columns-2', cols: 2, optionHtml: null},
      '1/3+2/3': {label: '2 columns', value: '1/3+2/3', class: 'kd_columns-2 kd_columns_partition-1-2', cols: 2, optionHtml: null},
      '2/3+1/3': {label: '2 columns', value: '2/3+1/3', class: 'kd_columns-2 kd_columns_partition-2-1', cols: 2, optionHtml: null},
      '1/3': {label: '3 columns', value: '1/3', class: 'kd_columns-3', cols: 3, optionHtml: null},
      '1/4': {label: '4 columns', value: '1/4', class: 'kd_columns-4', cols: 4, optionHtml: null},
      '1/5': {label: '5 columns', value: '1/5', class: 'kd_columns-5', cols: 5, optionHtml: null},
    };

    for(let optionIndex in this.allOptions) {
      let labelTpl = '<div class="col-indicator kd_columns %option_class%"><div class="kd_col"></div><div class="kd_col"></div><div class="kd_col"></div><div class="kd_col"></div><div class="kd_col"></div></div>';
      this.allOptions[optionIndex].optionHtml = labelTpl.replace('%option_class%', this.allOptions[optionIndex].class);
    }

    this.cachedColumnLayout = null;
    this.cachedColumnLayoutObject = null;
    if(!this.Obj.children) {
      this.Obj.children = [];
    }
    if(!this.Obj.options) {
      this.Obj.options = {};
      this.Obj.options.columnLayout = this.allOptions['1/2'];
    }
    // Emit to parent to force updating the view and buildingScripts.
    this.$emit('force-render');
    this.generateCols();
  }
}
</script>

<style lang="postcss" scoped>
@import '../sass/RowBlock/scoped';
</style>