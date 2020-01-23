<template>
  <div class="slider-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" @click="toggleOptionsPanel">
        <div class="tns-controls" aria-label="Carousel Navigation" tabindex="0">
          <button data-controls="prev" tabindex="-1" aria-controls="tns1" type="button"></button>
          <button data-controls="next" tabindex="-1" aria-controls="tns1" type="button"></button>
        </div>
        <div class="tns-ovh">
          <div class="tns-inner" id="tns1-iw">
            <div
              class="general-slider tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal"
              id="tns1"
              :class="numToShowClass"
              >
              <template v-for="index in Obj.options.numToShow">
                <div :key="'view-'+Obj.children[index - 1].id" class="tns-item kd_col" aria-hidden="true" tabindex="-1">
                  <div class="slide_container">
                    <div v-if="Obj.children[index - 1].component == 'TextFieldCollection'" class="text expandable" v-html="Obj.children[index - 1].contents.Text">
                    </div>
                    <a v-if="Obj.children[index - 1].component == 'ImageFieldCollection'" class="image-element">
                      <img :src="Obj.children[index - 1].contents.ImageSrc" :title="Obj.children[index - 1].contents.ImageTitle" :alt="Obj.children[index - 1].contents.ImageAlt" />
                    </a>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
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
        {{$options.label}}
      </div>
      <div class="console-container">
        <a
          class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
          :class="showContentBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
          @click = "showContentBody = !showContentBody"
          >
          <span>
          {{$options.label}} Contents:
          </span>
        </a>
      </div>
      <transition name="slide-fade">
        <div class="console-configbody" v-if="showContentBody">
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
          <div class="fieldCollectionControlBtns">
            <select v-model="targetElement">
              <option value="TextFieldCollection">Text</option>
              <option value="ImageFieldCollection">Image</option>
            </select>
            <a class="builder-add-element material-icons" @click="insertElement()" title="Add new accordion" style="line-height: 30px">
              add_circle_outline
            </a>
          </div>
        </div>
      </transition>

      <div class="console-container">
        <a
          class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
          :class="showConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
          @click = "showConfigBody = !showConfigBody"
          >
          <span>
          {{$options.label}} Options:
          </span>
        </a>
      </div>
      <transition name="slide-fade">
        <div class="console-configbody" v-if="showConfigBody">
          <label>How many slides to show in a row?</label>
          <select :value="Obj.options.numToShow" @blur="updateObj('numToShow', 'options', parseInt($event.target.value))">
            <option>1</option>
            <option>2</option>
            <option>3</option>
          </select>
        </div>
      </transition>

      <div class="option-console-container console-container">
        <a class="console-btn"><span>Update</span></a>
      </div>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
let elementsComponents = {
  // Async load components
  'TextFieldCollection': () => import('../FieldCollection/TextFieldCollection'),
  'ImageFieldCollection': () => import('../FieldCollection/ImageFieldCollection'),
};
export default {
  name: 'slider-element',
  label: 'Slider',

  mixins: [
    _mixins.block,
    _mixins.fieldCollection,
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.dragFix
  ],
  components: elementsComponents,
  data() {
    return {
      targetElement: 'TextFieldCollection',
    };
  },
  computed: {
    numToShowClass() {
      const num = this.Obj.options.numToShow;
      return 'slide-show-' + num;
    },
  },
  methods: {
    insertElement(text) {
      let targetElement = this.getElementTpl(this.targetElement);
      if(text) {
        targetElement.contents.Text = text;
      }
      this.Obj.children.push(targetElement);
      this.forceRenderThis();
    },
    forceRenderThis() {
      this.$set(this.Obj, 'refresh1', 1);
      this.$delete(this.Obj, 'refresh1');
    },
  },
  created() {
    if(!this.Obj.children) {
      // Defaults
      this.Obj.children = [];
      let targetElement = this.getElementTpl(this.targetElement);
      this.Obj.children.push(targetElement);
    }
    if(Object.keys(this.Obj.options).length == 0) {
      // Defaults
      this.Obj.options.numToShow = 1;
    }
  },
}
</script>

<style lang="postcss" scoped>
@import '../../sass/Elements/SliderElement/scoped';
</style>