<template>
  <div class="applet-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" class="text expandable"
        @click="toggleOptionsPanel"
      >
        <div v-html="Obj.contents.Text">
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
          <textarea
            placeholder="Type the text"
            @blur="updateObj('Text', 'contents', $event.target.value)"
            :value="Obj.contents.Text"></textarea>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'applet-element',
  label: 'Applet',

  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.elementOptions._bgColor,
    _mixins.elementOptions.iconblock_txtPosition,
    _mixins.elementOptions._icon
  ],
  data() {
    return {
    };
  },
  computed: {
    txtTitle() {
      const text = this.Obj.contents.Text;
      let tmp = text.replace(/(<([^>]+)>)/ig,"").replace('&nbsp;', ' ');
      return tmp.substring(0, 28) + '...';
    }
  },
  created() {
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.Text = '<h3>Applet Placeholder</h3>';
    }
  }
}
</script>