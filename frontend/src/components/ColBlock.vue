<template>
  <div class="col-block z-block z-component kd_col" :class="colClasses" @mouseover="colOnHover = true" @mouseout="colOnHover = false">
    <div class="builder-body">
      <draggable
        class="list-group"
        element="div"
        :list="Obj.children"
        :options="dragOptions"
        :move="onMove"
        @clone="startMove"
        @choose="applyDragFix"
        @end="onDragEnd($event);removeDragFix($event);">
        <template v-for="(child, index) in Obj.children">
          <component
            :is="child.component"
            :key="child.id"
            :elements="Obj.children"
            :elements-index="index"
            :building-scripts-info="buildingScriptsInfo"
            @force-render="forceRender"
            @remove="removeChild"
            @open-element-options-panel="openElementOptionsPanel"
          ></component>
        </template>
      </draggable>
      <div class="element-selector" :class="classObject">
        <a class="builder-add-element material-icons" @click="addElement()">
          add_circle_outline
        </a>
        <v-select
          class="elements-selection"
          :options="elementsOptions"
          label="label"
          v-model="selectedElement"
          @input="insertElement"></v-select>
      </div>
    </div>
    <div class="inline-element-control">
      <a class="icon icon_inline material-icons col-remove" title="Remove Column"
        @click="removeThis">delete_outline</a>
    </div>
  </div>
</template>

<script>
let elementsComponents = {
  // Async load components
  'IconBlockElement': () => import('./Elements/IconBlockElement'),
  'ButtonElement': () => import('./Elements/ButtonElement'),
  'HrElement': () => import('./Elements/HrElement'),
  'ImageElement': () => import('./Elements/ImageElement'),
  'TextElement': () => import('./Elements/TextElement'),
  'VideosElement': () => import('./Elements/VideosElement'),
  'AppletElement': () => import('./Elements/AppletElement'),
  'AccordionsElement': () => import('./Elements/AccordionsElement'),
  'WorkspaceInfoCardElement': () => import('./Elements/WorkspaceInfoCardElement'),
  'SliderElement': () => import('./Elements/SliderElement'),
};
import { default as _mixins } from '../mixins';

export default {
  name: 'col-block',
  props: ['columns', 'columnsIndex', 'buildingScriptsInfo'],
  components: elementsComponents,
  mixins: [
    _mixins.dragFix
  ],
  data() {
    let Obj = this.columns[this.columnsIndex];
    if(!Obj.children) {
      Obj.children = [];
    }
    return {
      // Ref to parent, so 2 way data binding.
      Obj: Obj,
      elementsOptions: [
        {value: "TextElement", label: "Text"},
        {value: "ImageElement", label: "Image"},
        {value: "VideosElement", label: "Videos"},
        {value: "ButtonElement", label: "Button"},
        {value: "IconBlockElement", label: "Icon Block"},
        {value: "AppletElement", label: "Applet"},
        {value: "AccordionsElement", label: "Accordions"},
        {value: "WorkspaceInfoCardElement", label: "Workspace Info Card"},
        {value: "SliderElement", label: "Slider"},
        {value: "HrElement", label: "Horizontal Line"},
      ],
      elementSelectionActive: false,
      selectedElement: null,
      colOnHover: false,

      // Draggable
      editable: true,
      isDragging: false,
      colWillBeEmpty: false,
    }
  },
  computed: {
    classObject() {
      const elementSelectionActive = this.elementSelectionActive;
      return {
        'state--expand-to-selection': elementSelectionActive
      };
    },
    colClasses() {
      const colOnHover = this.colOnHover;
      const children = this.Obj.children;
      return {
        'empty-col': children.length < 1 || this.colWillBeEmpty,
        'state--col-on-hover': colOnHover
      }
    },
    dragOptions () {
      return  {
        animation: 0,
        group: 'elements',
        disabled: !this.editable,
        ghostClass: 'ghost',
        dragClass: "sortable-drag",
        handle: '.preview'
      };
    }
  },
  methods: {
    addElement() {
      // Reset element selection
      this.selectedElement = null;

      // Open element selection
      this.elementSelectionActive = true;

    },
    insertElement(obs) {
      if(obs) {
        let D = new Date();
        this.Obj.children.push(
          {
            component: obs.value,
            id: Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime(),
            elementOptionsPanel: {
              active: false
            }
          }
        );
        // Close element selection
        this.elementSelectionActive = false;

        this.$emit('force-render');
      }
    },
    forceRenderThis() {
      this.$set(this.Obj, 'refresh1', 1);
      this.$delete(this.Obj, 'refresh1');
    },
    forceRender() {
      this.forceRenderThis();
      this.$emit('force-render');
    },
    removeChild(index) {
      this.Obj.children.splice(index, 1);
    },
    removeThis() {
      this.$emit('remove', this.columnsIndex);
    },
    openElementOptionsPanel(elementId) {
      this.$emit('open-element-options-panel', elementId);
    },
    onMove ({relatedContext, draggedContext}, event) {
      const relatedElement = relatedContext.element;
      const draggedElement = draggedContext.element;
      let isNowMoving = (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed;
      if(isNowMoving && this.Obj.children.length === 1) {
        this.colWillBeEmpty = true;
      }
      return isNowMoving;
    },
    onDragEnd() {
      this.colWillBeEmpty = false;
    },
    startMove(event) {

    },
  }
}
</script>

<style lang="postcss" scoped>
@import '../sass/ColBlock/scoped';
</style>