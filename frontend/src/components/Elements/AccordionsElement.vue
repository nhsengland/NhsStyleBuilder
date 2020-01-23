<template>
  <div class="accordions-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" class="kd_accordions"
        @click="toggleOptionsPanel"
      >
        <template v-for="(child, index) in Obj.children">
          <div :key="child.id" class="kd_accordion">
            <h2 :class="index === 0 ? 'active' : ''">{{child.contents.AccordionTitle}}</h2>
            <div :style="index === 0 ? 'display: block;' : 'display: none;'" v-html="child.contents.AccordionBody"></div>
          </div>
        </template>
      </div>
    </div>
    <div class="inline-element-control">
    </div>
    <div class="options" v-if="Obj.elementOptionsPanel.active">
      <div class="element-control">
        <a class="icon icon_inline material-icons float-left"
          @click="closeOptionsPanel">arrow_back</a>
        <a class="icon icon_inline material-icons float-right"
          @click="removeThis">delete_outline</a>
      </div>
      <div class="element-content console-container">
        {{$options.label}}:
      </div>
      <div class="console-container">
        <a
          class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
          :class="showConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
          @click = "showConfigBody = !showConfigBody"
          >
          <span>
          {{$options.label}} Contents:
          </span>
        </a>
      </div>
      <transition name="slide-fade">
        <div class="console-configbody" v-if="showConfigBody">
          <draggable
            class="list-group"
            element="div"
            :list="Obj.children"
            :options="dragOptions"
            @choose="applyDragFix"
            @end="removeDragFix($event);forceRenderThis();">
            <template v-for="(child, index) in Obj.children">
              <component
                :is="child.component"
                :key="child.id"
                :elements="Obj.children"
                :elements-index="index"
                @force-render="forceRenderThis"
                @remove="removeChild(index)"
              ></component>
            </template>
          </draggable>
          <br>
          <br>
          <a class="builder-add-element material-icons" @click="insertElement()" title="Add new accordion">
            add_circle_outline
          </a>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';

let elementsComponents = {
  // Async load components
  'AccordionFieldCollection': () => import('../FieldCollection/AccordionFieldCollection'),
};

export default {
  name: 'accordions-element',
  label: 'Accordions',

  mixins: [
    _mixins.block,
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.fieldCollection,
    _mixins.dragFix
  ],
  components: elementsComponents,
  methods: {
    insertElement() {
      let D = new Date();
      this.Obj.children.push(
        {
          component: 'AccordionFieldCollection',
          id: Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime(),
          contents: {
            AccordionTitle: '',
            AccordionBody: ''
          },
        }
      );
      this.forceRenderThis();
    },
    forceRenderThis() {
      this.$set(this.Obj, 'refresh1', 1);
      this.$delete(this.Obj, 'refresh1');
    },
  },
  created() {
    if(!this.Obj.children) {
      let D = new Date();
      this.Obj.children = [
        {
          'component': 'AccordionFieldCollection',
          id: Math.floor((Math.random() * 10000) + 1) + '-' + D.getTime(),
          contents: {
            AccordionTitle: 'Phasellus congue, magna vitae',
            AccordionBody: 'Phasellus congue, magna vitae faucibus rhoncus, augue neque eleifend turpis, quis eleifend lacus libero quis lorem.'
          }
        }
      ];
    }
  }
}
</script>