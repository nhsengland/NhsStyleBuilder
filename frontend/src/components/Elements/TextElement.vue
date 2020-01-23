<template>
  <div class="text-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" class="text expandable"
        @click="toggleOptionsPanel"
      >
        <div v-html="bodyText">
        </div>
        <div class="expandable_part">
          <div v-html="Obj.contents.TextCollapsed"></div>
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
        {{$options.label}}:
        <br />
        <br />
        <span class="title-style">{{txtTitle}}</span>
      </div>
      <div class="console-container">
        <vue-ckeditor
          v-if="showCkeditor"
          placeholder="Type the text"
          @blur="updateObj('Text', 'contents', $event._.data)"
          :config="config"
          :value="Obj.contents.Text" />
      </div>
      <div class="console-container">
        <a
          class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
          :class="showConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
          @click = "showConfigBody = !showConfigBody"
          >
          <span>
          {{$options.label}} collapsible text:
          </span>
        </a>
      </div>
      <transition name="slide-fade">
        <div class="console-configbody" v-if="showConfigBody">
          <vue-ckeditor
            v-if="showCkeditor"
            placeholder="Type the collapsible text"
            @blur="updateObj('TextCollapsed', 'contents', $event._.data)"
            :config="config"
            :value="Obj.contents.TextCollapsed" />
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'text-element',
  label: 'Text block',
  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.elementOptions._bgColor,
    _mixins.elementOptions.iconblock_txtPosition,
    _mixins.elementOptions._icon
  ],
  data() {
    return {
      config: {
        toolbar: [
          ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'Format', 'Styles', 'BulletedList', 'Link']
        ],
        height: 300
      },
    }
  },
  computed: {
    txtTitle() {
      const text = this.Obj.contents.Text;
      let tmp = text.replace(/(<([^>]+)>)/ig,"").replace('&nbsp;', ' ');
      return tmp.substring(0, 28) + '...';
    },
    bodyText() {
      const text = this.Obj.contents.Text;
      const collapsibleText = this.Obj.contents.TextCollapsed;
      let bodyText = text;
      if(collapsibleText) {
        bodyText += '<p><a class="expands" href="javascript:void(0);">Read More</a></p><br>';
      }
      return bodyText;
    }
  },
  created() {
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.Text = '<h2>Text</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>';
    }
  }
}
</script>